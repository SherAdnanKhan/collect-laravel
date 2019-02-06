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
use Illuminate\Database\Eloquent\Relations\Pivot;

class PersonSession extends Pivot
{
    protected $table = 'persons_to_sessions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'person_id', 'session_id', 'instrument_id', 'person_role_id',
    ];

    /**
     * Grab the instrument from this pivot.
     *
     * @return BelongsTo
     */
    public function instrument()
    {
        return $this->belongsTo(Instrument::class);
    }

    /**
     * Grab the person role from this pivot.
     *
     * @return BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(PersonRole::class, 'person_role_id');
    }

    /**
     * Grab the person from this pivot.
     *
     * @return BelongsTo
     */
    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    /**
     * Grab the session from this pivot.
     *
     * @return BelongsTo
     */
    public function session()
    {
        return $this->belongsTo(Session::class);
    }
}
