<?php

namespace App\Models;

use App\Models\Person;
use App\Models\PersonSession;
use App\Models\Project;
use App\Models\Recording;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    protected $fillable = [
        'project_id', 'studio', 'name', 'description',
    ];

    /**
     * The user who owns this song.
     *
     * @return BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the recordings associated to this session.
     *
     * @return BelongsToMany
     */
    public function recordings()
    {
        return $this->belongsToMany(Recording::class, 'sessions_to_recordings');
    }

    /**
     * Get the people in the session.
     *
     * @return BelongsToMany
     */
    public function people()
    {
        return $this->belongsToMany(Person::class, 'persons_to_sessions')
            ->using(PersonSession::class)->withPivot('person_role_id', 'instrument_id');
    }
}
