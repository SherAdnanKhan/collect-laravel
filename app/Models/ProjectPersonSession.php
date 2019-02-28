<?php

namespace App\Models;

use App\Models\Credit;
use App\Models\Instrument;
use App\Models\Person;
use App\Models\PersonRole;
use App\Models\PersonSession;
use App\Models\Recording;
use App\Models\Session;
use App\Models\Song;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProjectPersonSession extends Pivot
{
    protected $table = 'project_persons_to_sessions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_person_id', 'session_id', 'instrument_id', 'project_person_role_id',
    ];

    /**
     * Grab the instrument from this pivot.
     *
     * @return BelongsTo
     */
    public function instrument(): BelongsTo
    {
        return $this->belongsTo(Instrument::class);
    }

    /**
     * Grab the person role from this pivot.
     *
     * @return BelongsTo
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(PersonRole::class, 'person_role_id');
    }

    /**
     * Grab the person from this pivot.
     *
     * @return BelongsTo
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    /**
     * Grab the session from this pivot.
     *
     * @return BelongsTo
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(Session::class);
    }
}
