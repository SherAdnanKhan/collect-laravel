<?php

namespace App\Models;

use App\Contracts\UserAccessible;
use App\Models\Credit;
use App\Models\Person;
use App\Models\PersonSession;
use App\Models\Project;
use App\Models\ProjectPerson;
use App\Models\ProjectPersonSession;
use App\Models\Recording;
use App\Traits\UserAccesses;
use App\Util\BuilderQueries\ProjectAccess;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Session extends Model implements UserAccessible
{
    use UserAccesses;

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
     * Grab the credits/contributions directly on this resource.
     *
     * @return MorphMany
     */
    public function credits(): MorphMany
    {
        return $this->morphMany(Credit::class, 'contribution');
    }

    /**
     * A scope to filter the sessions by user access to a project.
     *
     * @param  Builder $query
     * @param  Model   $model
     * @return Builder
     */
    public function scopeUserViewable(Builder $query, $data = []): Builder
    {
        return (new ProjectAccess($query, auth()->user(), ['read']))->getQuery();
    }
}
