<?php

namespace App\Models;

use App\Models\Credit;
use App\Models\Recording;
use App\Models\Song;
use Illuminate\Database\Eloquent\Model;

class SongRecording extends Model
{
    protected $table = 'songs_to_recordings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'song_id', 'recording_id',
    ];

    /**
     * Get the song associated to this.
     *
     * @return Song
     */
    public function song()
    {
        return $this->belongsTo(Song::class);
    }

    /**
     * Get the recording associated to this.
     *
     * @return recording
     */
    public function recording()
    {
        return $this->belongsTo(Recording::class);
    }

    /**
     * Get all the credits for this song/recording instance.
     *
     * @return Collection
     */
    public function credits()
    {
        return $this->hasMany(Credit::class);
    }
}
