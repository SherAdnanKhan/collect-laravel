<?php

namespace App\Models;

use App\Contracts\UserAccessible;
use App\Models\Collaborators;
use App\Models\Credit;
use App\Models\Party;
use App\Models\Project;
use App\Models\Session;
use App\Models\Song;
use App\Models\SongRecording;
use App\Models\User;
use App\Traits\OrderScopes;
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
    use OrderScopes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id', 'party_id', 'song_id', 'name', 'type', 'description',
        'isrc', 'subtitle', 'version', 'recorded_on', 'mixed_on', 'duration',
        'language', 'key_signature', 'time_signature', 'tempo'
    ];

    protected $casts = [
        'recorded_on' => 'date',
        'mixed_on'    => 'date'
    ];

    /**
     * Get the project that this recording is associated to.
     *
     * @return BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the Party that this recording is associated to.
     *
     * @return BelongsTo
     */
    public function party(): BelongsTo
    {
        return $this->belongsTo(Party::class);
    }

    /**
     * Get the song that this recording is associated to.
     *
     * @return BelongsTo
     */
    public function song(): BelongsTo
    {
        return $this->belongsTo(Song::class);
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
     * Grab the credits/contributions directly on this resource.
     *
     * @return MorphMany
     */
    public function credits(): MorphMany
    {
        return $this->morphMany(Credit::class, 'contribution');
    }
}
