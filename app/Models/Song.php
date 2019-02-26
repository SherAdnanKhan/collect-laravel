<?php

namespace App\Models;

use App\Models\Project;
use App\Models\Recording;
use App\Models\SongRecording;
use App\Models\User;
use App\Util\BuilderQueries\ProjectAccess;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Song extends Model
{
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
        return $this->belongsToMany(Recording::class)->using(SongRecording::class);
    }

    /**
     * Get all of the projects that this song
     * is used in.
     *
     * @return BelongsToMany
     */
    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class)->using(SongRecording::class);
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
     * A scope to filter songs which are accesible by
     * the currently authed user.
     *
     * @param  Builder $query
     * @return Builder
     */
    public function scopeUserViewable(Builder $query): Builder
    {
        $user = auth()->user();

        // We check to see if the user is a collaborator or
        // owner on a project that this song has been used on or if
        // this song is owned by this user.
        return $query->whereHas('recordings', function($q) use ($user) {
            // We grab the project for the recording and check permission or
            // ownership access on that.
            return (new ProjectAccess($q, $user, ['read']))->getQuery();
        })->orWhere('user_id', $user->getAuthIdentifier())->orWhereNotNull('iswc');
    }
}
