<?php

namespace App\Models;

use App\Models\Credit;
use App\Models\Project;
use App\Models\Recording;
use App\Models\Song;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\Pivot;

class SongRecording extends Pivot
{
    protected $table = 'songs_to_recordings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'song_id', 'recording_id', 'project_id',
    ];

    /**
     * Get the song associated to this.
     *
     * @return BelongsTo
     */
    public function song(): BelongsTo
    {
        return $this->belongsTo(Song::class);
    }

    /**
     * Get the recording associated to this.
     *
     * @return BelongsTo
     */
    public function recording(): BelongsTo
    {
        return $this->belongsTo(Recording::class);
    }

    /**
     * Get the projects that this pivot table is for.
     *
     * @return BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get all the credits for this song/recording instance.
     *
     * @return HasMany
     */
    public function credits(): HasMany
    {
        return $this->hasMany(Credit::class);
    }
}
