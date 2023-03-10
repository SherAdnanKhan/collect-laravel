<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Session;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
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

    /**
     * Filter codes where the expiry date is in the future.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeNotExpired(Builder $query): Builder
    {
        return $query->where('expires_at', '>', Carbon::now()->timestamp);
    }

    /**
     * The key we're storing the access tokens for checkin.
     *
     * @param string $token
     * @return string
     */
    public static function checkinCacheKey($token): string
    {
        return sprintf('checkin.%s', $token);
    }

    /**
     * Generate a unique code
     *
     * @return string
     */
    public static function generateCode()
    {
        $code = [];
        $codeLength = 6;

        for ($i = 0; $i < $codeLength; $i++) {
            $code[] = rand(0, 9);
        }

        return join('', $code);
    }
}
