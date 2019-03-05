<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait UserAccesses
{
    public function scopeUserViewable(Builder $query, $data): Builder
    {
        return $query;
    }

    public function scopeUserUpdatable(Builder $query, $data): Builder
    {
        return $query;
    }

    public function scopeUserDeletable(Builder $query, $data): Builder
    {
        return $query;
    }
}
