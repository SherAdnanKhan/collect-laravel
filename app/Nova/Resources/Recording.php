<?php

namespace App\Nova\Resources;

use App\Nova\Resource;
use App\Nova\Resources\Party;
use App\Nova\Resources\RecordingType;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;

class Recording extends Resource
{
    public static $group = 'User Data';

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\\Models\\Recording';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    public static $with = ['project', 'song', 'type'];

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name', 'type.name', 'description',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make('Type', 'type', RecordingType::class),
            Text::make('User Defined Type Value', 'recording_type_user_defined_value'),

            BelongsTo::make('Project'),
            BelongsTo::make('Main Artist', 'party', Party::class),
            BelongsTo::make('Song'),

            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Sub Title', 'subtitle')
                ->sortable()
                ->rules('max:255'),

            Text::make('ISRC')
                ->sortable()
                ->rules('max:255'),

            Text::make('Version')
                ->sortable()
                ->rules('max:255'),

            Date::make('Recorded On')
                ->sortable(),

            Date::make('Mixed On')
                ->sortable(),

            Number::make('Duration')
                ->help('In Seconds')
                ->sortable()
                ->rules('max:255'),

            Text::make('Version')
                ->sortable()
                ->rules('max:255'),

            Text::make('Language Code', 'language')
                ->help('eg. en_US, en_GB, es_MX...')
                ->sortable()
                ->rules('max:20'),

            Text::make('Key Signature')
                ->rules('max:255'),

            Text::make('Time Signature')
                ->rules('max:255'),

            Number::make('Tempo')
                ->rules('max:255'),

            Textarea::make('Description')
                ->rules('max:255'),

            BelongsToMany::make('Sessions'),

            BelongsToMany::make('Collaborators', 'collaborators'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }

    /**
     * Determine if the current user can create new resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public static function authorizedToCreate(Request $request)
    {
        return false;
    }

    /**
     * Determine if the current user can update the given resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function authorizedToUpdate(Request $request)
    {
        return false;
    }

    /**
     * Determine if the current user can delete the given resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function authorizedToDelete(Request $request)
    {
        return false;
    }

    /**
     * Determine if the current user can view the given resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function authorizedToView(Request $request)
    {
        return true;
    }
}
