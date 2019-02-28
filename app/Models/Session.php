<?php

namespace App\Models;

use App\Models\Person;
use App\Models\PersonSession;
use App\Models\Project;
use App\Models\ProjectPerson;
use App\Models\ProjectPersonSession;
use App\Models\Recording;
use App\Util\BuilderQueries\ProjectAccess;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Session extends Model
{
    protected $fillable = [
        'project_id', 'studio', 'name', 'description',
    ];

    /**
     * The user who owns this song.
     *
     * @return BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the recordings associated to this session.
     *
     * @return BelongsToMany
     */
    public function recordings(): BelongsToMany
    {
        return $this->belongsToMany(Recording::class, 'sessions_to_recordings');
    }

    /**
     * Get the people in the session.
     *
     * @return BelongsToMany
     */
    public function people(): BelongsToMany
    {
        return $this->belongsToMany(ProjectPerson::class, 'project_persons_to_sessions')
            ->using(ProjectPersonSession::class)->withPivot('project_person_role_id', 'instrument_id');
    }

    /**
     * Where this session has been favourited/
     *
     * @return MorphMany
     */
    public function favourites(): MorphMany
    {
        return $this->morphMany(UserFavourite::class, 'favoured');
    }

    /**
     * A scope to filter the sessions by user access to a project.
     *
     * @param  Builder $query
     * @return Builder
     */
    public function scopeUserViewable(Builder $query): Builder
    {
        return (new ProjectAccess($query, auth()->user(), ['read']))->getQuery();
    }
}
