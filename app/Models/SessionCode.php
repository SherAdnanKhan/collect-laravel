<?php

namespace App\Models;

use App\Models\Session;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SessionCode extends Model
{
    /**
     * Mass-assignable fields
     *
     * @var array
     */
    protected $fillable = [
        'session_id', 'code', 'expires_at',
    ];

    /**
     * Casting fields to types.
     *
     * @var array
     */
    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * The session this code is for.
     *
     * @return BelongsTo
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(Session::class);
    }
}
