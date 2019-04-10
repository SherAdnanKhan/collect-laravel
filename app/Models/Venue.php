<?php

namespace App\Models;

use App\Contracts\UserAccessible;
use App\Models\Country;
use App\Models\User;
use App\Traits\UserAccesses;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Venue extends Model implements UserAccessible
{
    use UserAccesses;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'name', 'address', 'country_id'
    ];

    /**
     * Get the user who owns this venue.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the country this venue is in.
     *
     * @return BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Filter out Venues by user access
     *
     * @param  Builder $query
     * @param  array   $args
     * @return Builder
     */
    public function scopeUserViewable(Builder $query, $args = []): Builder
    {
        $user = $this->getUser($args);
        return $query->where('user_id', $user->getAuthIdentifier())->orWhere('approved');
    }
}
