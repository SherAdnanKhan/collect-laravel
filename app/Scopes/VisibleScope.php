<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;

/**
 * Apply this scope globally to models with a "hidden" field.
 */
class VisibleScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $builder->where('hidden', 0);
    }
}
