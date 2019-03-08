<?php

namespace App\Models;

use App\Contracts\UserAccessible;
use App\Models\Credit;
use App\Models\Person;
use App\Models\PersonSession;
use App\Models\Project;
use App\Models\Recording;
use App\Models\SessionType;
use App\Models\Venue;
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
        'project_id', 'session_type_id', 'venue_id', 'name', 'description', 'started_at',
        'ended_at', 'union_session', 'analog_session', 'drop_frame', 'venue_room', 'bitdepth',
        'samplerate', 'timecode_type', 'timecode_frame_rate'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at'   => 'datetime'
    ];

    /**
     * The project who owns this song.
     *
     * @return BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * The venue who owns this song.
     *
     * @return BelongsTo
     */
    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    /**
     * The type of the session
     * @return BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(SessionType::class, 'session_type_id');
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
}
