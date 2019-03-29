<?php

namespace App\Http\Controllers\Webhooks;

use App\Jobs\Emails\SendSubscriptionCancelledEmail;
use App\Jobs\Emails\SendSubscriptionPaymentFailedEmail;
use App\Jobs\Emails\SendSubscriptionPaymentSuccessfulEmail;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;

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
            })->each(function ($subscription) {
                // Dispatch the sending of the payment success email.
                SendSubscriptionPaymentSuccessfulEmail::dispatch($user, $subscription);
            });
        }

        return new Response('Webhook Handled', 200);
    }
}
