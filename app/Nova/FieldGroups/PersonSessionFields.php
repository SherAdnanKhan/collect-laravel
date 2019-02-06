<?php

namespace App\Nova\FieldGroups;

use App\Models\Instrument;
use App\Models\PersonRole;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Select;

class PersonSessionFields
{
    /**
     * Get the pivot fields for the relationship.
     *
     * @return array
     */
    public function __invoke()
    {
        $roles = PersonRole::all()->pluck('name', 'id');
        $instruments = Instrument::all()->pluck('name', 'id');

        return [
            Select::make('Role', 'person_role_id')->options($roles),
            Select::make('Instrument', 'instrument_id')->options($instruments),
        ];
    }
}
