<?php

namespace App\Models;

use App\Models\Recording;
use App\Models\SongRecording;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    protected $fillable = [
        'user_id', 'iswc', 'title', 'type', 'subtitle',
        'genre', 'artist'
    ];

    /**
     * The user who owns this song.
     *
     * @return User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all occurences of a recording of this song.
     *
     * @return Collection
     */
    public function recordings()
    {
        return $this->hasManyThrough(Recording::class, SongRecording::class);
    }

    /**
     * Get the songs favourite row
     */
    public function favourites()
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
            // We grab the project for the recording
            return $q->whereHas('project', function($q) use ($user) {
                // Grab the collaborators for the project
                return $q->whereHas('collaborators', function($q) use ($user) {
                    // Check the users permissions
                    return $q->where('user_id', $user->id)->whereHas('permissions', function($q) {
                        return $q->where('level', 'read');
                    });
                })->orWhere('user_id', $user->id);
            });
        })->orWhere('user_id', $user->id);
    }
}
