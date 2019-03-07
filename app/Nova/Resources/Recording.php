<?php

namespace App\Nova\Resources;

use App\Nova\Resource;
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

    public static $with = ['project'];

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name', 'type', 'description',
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

            BelongsTo::make('Project'),
            BelongsTo::make('Main Artist', 'person'),
            BelongsTo::make('Song'),

            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Sub Title', 'subtitle')
                ->sortable()
                ->rules('max:255'),

            Text::make('Type')
                ->sortable()
                ->rules('required', 'max:255'),

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
     * Determine if the given resource is authorizable.
     *
     * @return bool
     */
    public static function authorizable()
    {
        return false;
    }
}
