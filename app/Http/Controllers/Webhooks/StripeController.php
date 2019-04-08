<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\GraphQL\Mutations\Subscription\GraphQLSubscription;
use App\Jobs\Emails\SendSubscriptionCancelledEmail;
use App\Jobs\Emails\SendSubscriptionPaymentFailedEmail;
use App\Jobs\Emails\SendSubscriptionPaymentSuccessfulEmail;
use App\Jobs\Emails\SendSubscriptionUpdatedEmail;
use App\Models\Subscription;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;
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
        $user = $this->getUserByStripeId($payload['data']['object']['customer']);

        if ($user) {
            $user->subscriptions->filter(function ($subscription) use ($payload) {
                return $subscription->stripe_id === $payload['data']['object']['id'];
            })->each(function ($subscription) {
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
        $user = $this->getUserByStripeId($payload['data']['object']['customer']);

        if ($user) {
            $user->subscriptions->filter(function ($subscription) use ($payload) {
                return $subscription->stripe_id === $payload['data']['object']['id'];
            })->each(function ($subscription) {
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
        $user = $this->getUserByStripeId($payload['data']['object']['customer']);

        if ($user) {
            $user->subscriptions->filter(function ($subscription) use ($payload) {
                return $subscription->stripe_id === $payload['data']['object']['id'];
            })->each(function ($subscription) use ($payload) {

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
        $user = $this->getUserByStripeId($payload['data']['object']['customer']);

        if ($user) {
            $data = $payload['data']['object'];

            $user->subscriptions->filter(function (Subscription $subscription) use ($data) {
                return $subscription->stripe_id === $data['id'];
            })->each(function (Subscription $subscription) use ($data) {
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
                if ($data['plan']['id'] !== $subscription->stripe_plan && $subscription->stripe_plan !== 'free') {
                    SendSubscriptionUpdatedEmail::dispatch($user, $subscription);
                }

                // Send cancelled email if they downgrade to free plan
                if ($data['plan']['id'] === 'free' && $originalStripePlan !== 'free') {
                    SendSubscriptionCancelledEmail::dispatch($user, $subscription);
                }
            });
        }

        return new Response('Webhook Handled', 200);
    }
}
