<?php

namespace App\Nova\FieldGroups;

use App\Nova\Resources\PersonRole;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;

class PersonSessionFields
{
    /**
     * Get the pivot fields for the relationship.
     *
     * @return array
     */
    public function __invoke()
    {
        return [
            // TODO: Work out how to set properties on a pivot with a relation.
            BelongsTo::make('Person Role', 'role', PersonRole::class),

            // BelongsTo::make('Instrument'),
        ];
    }
}
