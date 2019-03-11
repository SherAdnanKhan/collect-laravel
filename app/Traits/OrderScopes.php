<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait OrderScopes
{
    /**
     * A scope to order rows with created_at desc
     *
     * @param  Builder $query
     * @param  array   $args
     * @return Builder
     */
    public function scopeRecent(Builder $query, $args = []): Builder
    {
        return $query->latest();
    }

    /**
     * A scope to order rows with created_at asc
     *
     * @param  Builder $query
     * @param  array   $args
     * @return Builder
     */
    public function scopeOlder(Builder $query, $args = []): Builder
    {
        return $query->oldest();
    }
}
