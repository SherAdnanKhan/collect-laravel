<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait OrderScopes
{
    /**
     * Order by a field.
     *
     * @param  Builder $query
     * @param  array   $args
     * @return Builder
     */
    public function scopeOrderByField(Builder $query, $args = []): Builder
    {
        $fieldName = array_get($args, 'ordering.field');
        $direction = array_get($args, 'ordering.direction', 'desc');

        if (!is_null($fieldName)) {
            return $query->orderBy($fieldName, $direction);
        }

        return $query;
    }

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
