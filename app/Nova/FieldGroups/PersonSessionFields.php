<?php

namespace App\Nova\FieldGroups;

use App\Models\Instrument;
use App\Models\Person;
use App\Models\PersonRole;
use App\Models\Session;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Select;

class PersonSessionFields
{
    /**
     * Get the pivot fields for the relationship.
     *
     * @param  Request $request
     * @param  Model $resource
     * @return array
     */
    public function __invoke($request, $resource)
    {
        $roles = PersonRole::all()->pluck('name', 'id');
        $instruments = Instrument::all()->pluck('name', 'id');

        $fields = [];

        if ($resource instanceof Person) {
            $people = Person::all()->pluck('name', 'id');
            $fields[] = Select::make('Person', 'person_id')->options($people)->hideFromIndex();
        }

        if ($resource instanceof Session) {
            $sessions = Session::all()->pluck('name', 'id');
            $fields[] = Select::make('Session', 'session_id')->options($sessions)->hideFromIndex();
        }

        return array_merge($fields, [
            Select::make('Role', 'person_role_id')->options($roles)->displayUsing(function($value) use ($roles) {
                return $roles->get($value, '-');
            }),
            Select::make('Instrument', 'instrument_id')->options($instruments)->displayUsing(function($value) use ($instruments) {
                return $instruments->get($value, '-');
            }),
        ]);
    }
}
