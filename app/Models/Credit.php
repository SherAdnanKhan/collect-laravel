<?php

namespace App\Models;

use App\Models\Person;
use App\Models\SongRecording;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Credit extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'person_id', 'contribution_id', 'contribution_type', 'role', 'performing',
    ];

    /**
     * Get the contributed resource.
     *
     * @return MorphTo
     */
    public function contribution(): MorphTo
    {
        return $this->morphTo(null, 'contribution_type', 'contribution_id');
    }

    /**
     * Get the person who is being credited.
     *
     * @return BelongsTo
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }
}
