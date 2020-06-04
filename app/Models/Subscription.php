<?php

namespace App\Models;

use Stripe\Plan;
use Stripe\Stripe;
use Stripe\Invoice;
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
     * A property to cache the result of fetching the
     * subscriptions latest invoice.
     *
     * @var Stripe\Invoice
     */
    private $latestInvoice = null;

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

    /**
     * Get the latest invoice for the subscription.
     *
     * @return Stripe\Invoice
     */
    public function getLatestInvoice($force = false): Invoice
    {
        if (is_null($this->latestInvoice) || $force) {
            Stripe::setApiKey(config('services.stripe.secret'));

            $subscription = $this->asStripeSubscription();
            $latestInvoiceId = $subscription->latest_invoice;

            Log::debug('invoice id ' . $latestInvoiceId);

            $this->latestInvoice = Invoice::retrieve($latestInvoiceId);
        }

        return $this->latestInvoice;
    }

    /**
     * Get the total amount paid on the latest invoice.
     *
     * @return String
     */
    public function getLatestInvoiceAmountFormatted()
    {
        $invoice = $this->getLatestInvoice();

        $formatter = new \NumberFormatter("en-US", \NumberFormatter::CURRENCY);
        $amountFormatted = $formatter->formatCurrency($invoice->total / 100, strtoupper($invoice->currency));

        return $amountFormatted;
    }
}
