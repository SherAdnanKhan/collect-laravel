<?php

namespace App\Nova\Resources;

use App\Nova\Resource;
// use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;

class CollaboratorPermission extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\\Models\\CollaboratorPermission';

    public static $displayInNavigation = false;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'level';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'type', 'level',
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

            BelongsTo::make('Collaborator'),

            Select::make('On Resource', 'type')->options([
                'project'      => 'Projects',
                'file'         => 'Files & Folders',
                'session'      => 'Sessions',
                'recording'    => 'Recordings',
                'collaborator' => 'Collaborators',
            ])->displayUsingLabels()->rules('required'),

            Select::make('Level')->options([
                'read'     => 'Read',
                'create'   => 'Create',
                'update'   => 'Update',
                'delete'   => 'Delete',
                'download' => 'Download',
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
