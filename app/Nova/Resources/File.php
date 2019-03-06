<?php

namespace App\Nova\Resources;

use App\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
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

class File extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\\Models\\File';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name', 'type', 'path'
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

            BelongsTo::make('User'),
            BelongsTo::make('Project'),

            BelongsTo::make('Folder')->nullable(),

            Text::make('Name')
                ->sortable()
                ->rules('required'),

            Text::make('Type')
                ->sortable()
                ->rules('required'),

            Text::make('Path')
                ->sortable()
                ->rules('required'),

            // Text::make('Transcoded Path')
            //     ->sortable()
            //     ->rules('required'),

            Number::make('Bitrate')
                ->sortable(),

            Number::make('Bitdepth')
                ->sortable(),

            Number::make('Sample Rate', 'samplerate')
                ->sortable(),

            Number::make('Duration')
                ->sortable(),

            Number::make('Number of Channels', 'numchans')
                ->sortable(),

            Number::make('Size')
                ->sortable(),

            Select::make('Status')->options([
                \App\Models\File::STATUS_PENDING    => 'Pending',
                \App\Models\File::STATUS_PROCESSING => 'Processing',
                \App\Models\File::STATUS_COMPLETE   => 'Complete',
            ])->displayUsingLabels()->rules('required'),
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
