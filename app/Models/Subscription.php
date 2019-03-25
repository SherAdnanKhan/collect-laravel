<?php

namespace App\Models;

use App\Contracts\UserAccessible;
use App\Traits\UserAccesses;
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
}
