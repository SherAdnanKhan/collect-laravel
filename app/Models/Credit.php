<?php

namespace App\Models;

use App\Models\SongRecording;
use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    // TODO:
    // Associate credits polymorphically to each resource
    // song, recording, project, session and relate
    // to a person.

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'song_recording_id', 'role', 'name', 'email',
    ];

    /**
     * Get the song/recording instance this credit is for.
     *
     * @return SongRecording
     */
    public function songRecording()
    {
        return $this->belongsTo(SongRecording::class, 'song_recording_id');
    }
}
