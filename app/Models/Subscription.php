<?php

namespace App\Models;

use Stripe\Plan;
use App\Models\User;
use App\Traits\UserAccesses;
use App\Contracts\UserAccessible;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Subscription as CashierSubscription;

/**
 * Wrap the existing cashier subscription model.
 */
class Subscription extends CashierSubscription implements UserAccessible
{
    use UserAccesses;

    /**
     * Filter subscriptions to those owned by the currently authed user.
     *
     * @param  Builder $query
     * @param  array   $args
     * @return Builder
     */
    public function scopUserViewable(Builder $query, $args = []): Builder
    {
        return $query->where('user_id', auth()->user()->getAuthIdentifier());
    }

    /**
     * Get the stripe plan for this subscription.
     *
     * @return \Stripe\Plan
     */
    public function getStripePlan(): Plan
    {
        $stripeSubscription = $this->asStripeSubscription();

        return Plan::retrieve($stripeSubscription->plan->id, User::getStripeKey());
    }

    /**
     * Get the amount the current plan cost formatted
     *
     * @return string
     */
    public function getPlanCostFormatted($formatted = false)
    {
        $plan = $this->getStripePlan();

        $formatter = new \NumberFormatter("en-US", \NumberFormatter::CURRENCY);
        $amountFormatted = $formatter->formatCurrency($plan->amount / 100, strtoupper($plan->currency));

        return $amountFormatted;
    }
}
