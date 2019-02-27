<?php

namespace App\Models;

use App\Models\Collaborators;
use App\Models\PersonSession;
use App\Models\ProjectPersonRole;
use App\Models\Session;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class ProjectPerson extends Model
{
    protected $table = 'project_persons';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id', 'name', 'email',
    ];

    /**
     * The Project of this person
     *
     * @return BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get all sessions this projects person is associated to.
     *
     * @return BelongsToMany
     */
    public function sessions(): BelongsToMany
    {
        return $this->belongsToMany(Session::class, 'project_persons_to_sessions')
            ->using(ProjectPersonSession::class);
    }

    /**
     * Get all roles this projects person has
     *
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(ProjectPersonRole::class, 'project_persons_to_sessions')
            ->using(ProjectPersonSession::class);
    }

    /**
     * A scope to filter visible people by user ownership.
     *
     * @param  Builder $query
     * @return Builder
     */
    public function scopeUserViewable(Builder $query): Builder
    {
        return (new ProjectAccess($query, auth()->user(), ['read']))->getQuery();
    }
}
