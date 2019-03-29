<?php

namespace App\Http\Controllers\Webhooks;

use App\Jobs\Emails\SendSubscriptionCancelledEmail;
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
}
