<?php

namespace App\Http\Controllers\Webhooks;

use App\Jobs\Emails\SendSubscriptionCancelledEmail;
use App\Jobs\Emails\SendSubscriptionPaymentFailedEmail;
use App\Jobs\Emails\SendSubscriptionPaymentSuccessfulEmail;
use App\Jobs\Emails\SendSubscriptionUpdatedEmail;
use App\Models\Subscription;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;
use Nuwave\Lighthouse\Execution\Utils\Subscription as GraphQLSubscription;
use Symfony\Component\HttpFoundation\Response;

/**
 * Handle stripe webhook requests.
 */
class StripeController extends CashierController
{
    // Override methods from the cashier controller when we want to do
    // something different.


    /**
     * Handle a cancelled customer from a Stripe subscription.
     *
     * @override
     * @param  array  $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handleCustomerSubscriptionDeleted(array $payload)
    {
        Log::debug('Received customer subscription deleted webhook');

        $user = $this->getUserByStripeId($payload['data']['object']['customer']);

        if ($user) {
            $user->subscriptions->filter(function ($subscription) use ($payload) {
                return $subscription->stripe_id === $payload['data']['object']['id'];
            })->each(function ($subscription) use ($user) {
                Log::debug(sprintf('Mark subscription for %s as cancelled', $subscription->user_id));

                $subscription->markAsCancelled();

                // Dispatch the sending of the cancelled email.
                SendSubscriptionCancelledEmail::dispatch($user, $subscription);
            });
        }

        return new Response('Webhook Handled', 200);
    }

    /**
     * Behaviour for payments failed.
     *
     * @param  array $payload
     * @return Response
     */
    protected function handleInvoicePaymentFailed(array $payload)
    {
        Log::debug('Received invoice payment failed webhook');

        $user = $this->getUserByStripeId($payload['data']['object']['customer']);

        if ($user) {
            $user->subscriptions->filter(function ($subscription) use ($payload) {
                return $subscription->stripe_id === $payload['data']['object']['subscription'];
            })->each(function ($subscription) use ($user) {
                Log::debug('Send subscription payment failed email');

                // Dispatch the sending of the payment failed email.
                SendSubscriptionPaymentFailedEmail::dispatch($user, $subscription);
            });
        }

        return new Response('Webhook Handled', 200);
    }

    /**
     * Behaviour for payments failed.
     *
     * @param  array $payload
     * @return Response
     */
    protected function handleInvoicePaymentSucceeded(array $payload)
    {
        Log::debug('Received invoice payment succeeded webhook');

        $user = $this->getUserByStripeId($payload['data']['object']['customer']);

        if ($user) {
            $user->subscriptions->filter(function ($subscription) use ($payload) {
                return $subscription->stripe_id === $payload['data']['object']['subscription'];
            })->each(function ($subscription) use ($payload, $user) {
                Log::debug('Send subscription payment successful email');

                if ($payload['data']['object']['amount_paid'] == 0) {
                    return true;
                }

                $formatter = new \NumberFormatter("en-US", \NumberFormatter::CURRENCY);
                $invoiceAmountPaid = $formatter->formatCurrency($payload['data']['object']['amount_paid'] / 100, strtoupper($payload['data']['object']['currency']));
                $invoiceUrl = $payload['data']['object']['invoice_pdf'];

                // Dispatch the sending of the payment success email.
                SendSubscriptionPaymentSuccessfulEmail::dispatch($user, $subscription, $invoiceAmountPaid, $invoiceUrl);
            });
        }

        return new Response('Webhook Handled', 200);
    }

    /**
     * Handle customer subscription updated.
     *
     * @param  array $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handleCustomerSubscriptionUpdated(array $payload)
    {
        Log::debug('Received customer subscription updated webhook');

        $user = $this->getUserByStripeId($payload['data']['object']['customer']);

        if ($user) {
            $data = $payload['data']['object'];

            $user->subscriptions->filter(function (Subscription $subscription) use ($data) {
                return $subscription->stripe_id === $data['id'];
            })->each(function (Subscription $subscription) use ($data, $user) {
                Log::debug('Update customer subscription');

                $originalStripePlan = $subscription->stripe_plan;

                // Quantity...
                if (isset($data['quantity'])) {
                    $subscription->quantity = $data['quantity'];
                }

                // Plan...
                if (isset($data['plan']['id'])) {
                    $subscription->stripe_plan = $data['plan']['id'];
                }

                // Trial ending date...
                if (isset($data['trial_end'])) {
                    $trial_ends = Carbon::createFromTimestamp($data['trial_end']);

                    if (! $subscription->trial_ends_at || $subscription->trial_ends_at->ne($trial_ends)) {
                        $subscription->trial_ends_at = $trial_ends;
                    }
                }

                // Cancellation date...
                if (isset($data['cancel_at_period_end']) && $data['cancel_at_period_end']) {
                    $subscription->ends_at = $subscription->onTrial()
                                ? $subscription->trial_ends_at
                                : Carbon::createFromTimestamp($data['current_period_end']);
                }

                $subscription->save();

                GraphQLSubscription::broadcast('userSubscriptionUpdated', $subscription);

                // Send subscription updated email if they're not on the free plan
                if ($originalStripePlan !== $subscription->stripe_plan && $subscription->stripe_plan !== 'free') {
                    Log::debug('Send subscription updated email');

                    SendSubscriptionUpdatedEmail::dispatch($user, $subscription);
                }

                // Send cancelled email if they downgrade to free plan
                if ($data['plan']['id'] === 'free' && $originalStripePlan !== 'free') {
                    Log::debug('Send subscription cancelled email');

                    SendSubscriptionCancelledEmail::dispatch($user, $subscription);
                }
            });
        }

        return new Response('Webhook Handled', 200);
    }
}
