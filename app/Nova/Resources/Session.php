<?php

namespace App\Nova\Resources;

use App\Nova\Resource;
use App\Nova\Resources\Person;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
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

class Session extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\\Models\\Session';

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
        'name', 'started_at', 'ended_at'
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

            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),

            DateTime::make('Started At')
                ->sortable()
                ->rules('required', 'max:255'),

            DateTime::make('Ended At')
                ->sortable()
                ->rules('required', 'max:255'),

            BelongsTo::make('Session Type', 'type'),

            Boolean::make('Union Session')
                ->onlyOnForms(),
            Boolean::make('Analog Session')
                ->onlyOnForms(),

            BelongsTo::make('Venue'),

            Text::make('Venue Room')
                ->onlyOnForms()
                ->rules('max:255'),

            Number::make('Bit Depth', 'bitdepth')
                ->onlyOnForms(),

            Number::make('Sample Rate', 'samplerate')
                ->onlyOnForms(),

            Text::make('Timecode Type')
                ->onlyOnForms()
                ->rules('max:255'),

            Text::make('Timecode Frame Rate')
                ->onlyOnForms()
                ->rules('max:255'),

            Boolean::make('Drop Frame')
                ->onlyOnForms(),

            Textarea::make('Description')
                ->onlyOnForms()
                ->rules('max:255'),

            BelongsToMany::make('Recordings'),
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
}
