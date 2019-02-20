<?php

namespace App\Models;

use App\Models\Recording;
use App\Models\SongRecording;
use App\Models\User;
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
}
