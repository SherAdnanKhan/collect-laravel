<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAffiliation extends Model
{
    const STATUS_UNVERIFIED = 'unverified';
    const STATUS_PENDING = 'pending';
    const STATUS_VERIFIED = 'verified';
    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id', 'affiliation_id', 'number', 'status'
    ];

    protected $attributes = [
        'status' => self::STATUS_UNVERIFIED
    ];

    /**
     * Get the owning user of this model.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the owning user of this model.
     *
     * @return BelongsTo
     */
    public function affiliation(): BelongsTo
    {
        return $this->belongsTo(Affiliation::class);
    }

    /**
     * @return string[]
     */
    public static function getStatusList(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_UNVERIFIED,
            self::STATUS_VERIFIED,
        ];
    }
}
