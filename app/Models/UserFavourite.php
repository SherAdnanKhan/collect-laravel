<?php

namespace App\Models;

use App\Contracts\UserAccessible;
use App\Models\User;
use App\Traits\UserAccesses;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class UserFavourite extends Model implements UserAccessible
{
    use UserAccesses;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'resource_type', 'resource_id',
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
     * Get all of the owning resource models.
     */
    public function favoured(): MorphTo
    {
        return $this->morphTo(null, 'resource_type', 'resource_id');
    }

    /**
     * Only the user who's favourite this is can see it.
     *
     * @param  Builder $query
     * @param  array   $data
     * @return Builder
     */
    public function scopeUserViewable(Builder $query, $data = []): Builder
    {
        $user = auth()->user();
        return $query->where('user_id', $user->getAuthIdentifier());
    }

    /**
     * Only the user who's favourite this is can delete it.
     *
     * @param  Builder $query
     * @param  array   $data
     * @return Builder
     */
    public function scopeUserDeletable(Builder $query, $data = []): Builder
    {
        $user = auth()->user();
        return $query->where('user_id', $user->getAuthIdentifier());
    }
}
