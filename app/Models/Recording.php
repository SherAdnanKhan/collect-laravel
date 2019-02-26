<?php

namespace App\Models;

use App\Models\Collaborators;
use App\Models\Project;
use App\Models\Session;
use App\Models\User;
use App\Util\BuilderQueries\ProjectAccess;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Recording extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id', 'name', 'type', 'description',
    ];

    /**
     * Get the project that this recording is asociated to.
     *
     * @return Project
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the sessions associated to this session.
     *
     * @return BelongsToMany
     */
    public function sessions()
    {
        return $this->belongsToMany(Session::class, 'sessions_to_recordings');
    }

    public function favourites()
    {
        return $this->morphMany(UserFavourite::class, 'favoured');
    }

    /**
     * A scope to filter recordings with which the current user has
     * access to view, either by ownership or read access
     *
     * @param  Builder $query
     * @return Builder
     */
    public function scopeUserViewable(Builder $query): Builder
    {
        $user = auth()->user();

        // Check to see if the current user owns or
        // has read access as a collaborator on the project
        // with which this recording is in.
        return (new ProjectAccess($q, $user, ['read']))->getQuery();
    }
}
