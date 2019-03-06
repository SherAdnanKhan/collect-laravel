<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface UserAccessible
{
    public function scopeUserViewable(Builder $query, $data = []): Builder;
    public function scopeUserUpdatable(Builder $query, $data = []): Builder;
    public function scopeUserDeletable(Builder $query, $data = []): Builder;
}
