<?php

namespace App\Models;

use App\Contracts\UserAccessible;
use App\Models\Credit;
use App\Models\Project;
use App\Models\Recording;
use App\Models\SongRecording;
use App\Models\User;
use App\Traits\OrderScopes;
use App\Traits\UserAccesses;
use App\Util\BuilderQueries\ProjectAccess;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Song extends Model implements UserAccessible
{
    use UserAccesses;
    use OrderScopes;

    protected $fillable = [
        'user_id', 'iswc', 'title', 'type', 'subtitle',
        'genre', 'artist'
    ];

    /**
     * The user who owns this song.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all occurences of a recording of this song.
     *
     * @return BelongsToMany
     */
    public function recordings(): BelongsToMany
    {
        return $this->belongsToMany(Recording::class, 'songs_to_recordings')->using(SongRecording::class);
    }

    /**
     * Get all of the projects that this song
     * is used in.
     *
     * @return BelongsToMany
     */
    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'songs_to_recordings', 'song_id', 'project_id')->using(SongRecording::class);
    }

    /**
     * Get the songs favourite row
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
     * A scope to filter songs which are accesible by
     * the currently authed user.
     *
     * @param  Builder $query
     * @param  Model  $model
     * @return Builder
     */
    public function scopeUserViewable(Builder $query, $data = []): Builder
    {
        $user = auth()->user();

        // We check to see if the user is a collaborator or
        // owner on a project that this song has been used on or if
        // this song is owned by this user.
        return $query->whereHas('recordings', function($q) use ($user) {
            // We grab the project for the recording and check permission or
            // ownership access on that.
            return (new ProjectAccess($q, $user, ['recording'], ['read']))->getQuery()
                ->select(['recordings.id', 'recordings.project_id']);
        })->orWhere('user_id', $user->getAuthIdentifier())->orWhereNotNull('iswc');
    }

    /**
     * A scope to filter songs which are updatable by
     * the currently authed user.
     *
     * @param  Builder $query
     * @param  Model  $model
     * @return Builder
     */
    public function scopeUserUpdatable(Builder $query, $data = []): Builder
    {
        $user = auth()->user();

        // User must own a song to update it
        return $query->where('user_id', $user->getAuthIdentifier());
    }

    /**
     * A scope to filter songs which are deletable by
     * the currently authed user.
     *
     * @param  Builder $query
     * @param  Model  $model
     * @return Builder
     */
    public function scopeUserDeletable(Builder $query, $data = []): Builder
    {
        $user = auth()->user();

        // User must own a song to delete it.
        return $query->where('user_id', $user->getAuthIdentifier());
    }
}
