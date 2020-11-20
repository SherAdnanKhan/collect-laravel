<?php

namespace App\Models;

use App\Contracts\UserAccessible;
use App\Traits\UserAccesses;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Share extends Model implements UserAccessible
{
    use UserAccesses;

    const STATUS_NEW = "new";
    const STATUS_LIVE = "live";
    const STATUS_CANCELLED = "cancelled";
    const STATUS_EXPIRED = "expired";

    protected $fillable = ['user_id', 'project_id', 'size', 'download_count', 'path', 'password', 'message', 'complete', 'status', 'expires_at'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            $post->{$post->getKeyName()} = (string) Str::uuid();
        });
    }

    protected $casts = [
        'expires_at' => 'datetime'
    ];

    public function getIncrementing()
    {
        return false;
    }

    public function getKeyType()
    {
        return 'string';
    }

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
     * Get the owning project of this model.
     *
     * @return BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the files of this model.
     *
     * @return hasMany
     */
    public function files(): hasMany
    {
        return $this->hasMany(ShareFile::class);
    }

    /**
     * Get the users of this model.
     *
     * @return HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(ShareUser::class);
    }

    public function hasExpired()
    {
        return $this->status !== self::STATUS_LIVE;
    }
}
