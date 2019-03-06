<?php

namespace App\Models;

use App\Contracts\UserAccessible;
use App\Models\Collaborators;
use App\Models\Credit;
use App\Models\Project;
use App\Models\Session;
use App\Models\Song;
use App\Models\SongRecording;
use App\Models\User;
use App\Traits\UserAccesses;
use App\Util\BuilderQueries\ProjectAccess;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Recording extends Model implements UserAccessible
{
    use UserAccesses;

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
     * @return BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the sessions associated to this session.
     *
     * @return BelongsToMany
     */
    public function sessions(): BelongsToMany
    {
        return $this->belongsToMany(Session::class, 'sessions_to_recordings');
    }

    /**
     * Get where this recording has been favoured.
     *
     * @return MorphMany
     */
    public function favourites(): MorphMany
    {
        return $this->morphMany(UserFavourite::class, 'favoured');
    }

    /**
     * Get the songs for this recording.
     *
     * @return HasMany
     */
    public function songs(): HasMany
    {
        return $this->hasMany(Song::class)->using(SongRecording::class)->withPivot('project_id');
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
}
