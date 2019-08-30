<?php

namespace App\Models;

use App\Contracts\UserAccessible;
use App\Models\Party;
use App\Models\Country;
use App\Traits\UserAccesses;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Represent a parties addresses.
 */
class PartyAddress extends Model implements UserAccessible
{

    use UserAccesses;

    protected $fillable = [
        'party_id', 'line_1', 'line_2', 'line_3',
        'city', 'district', 'postal_code', 'territory_code_id',
    ];

    /**
     * The party with which this address belongs.
     *
     * @return BelongsTo
     */
    public function party(): BelongsTo
    {
        return $this->belongsTo(Party::class);
    }

    /**
     * The country for this address.
     *
     * @return BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * A party address is viewable to everyone.
     *
     * @param  Builder $query
     * @param  array   $data
     * @return Builder
     */
    public function scopeUserViewable(Builder $query, $data = []): Builder
    {
        return $query;
    }

    /**
     * A user can update a party address if they own it.
     *
     * @param  Builder $query
     * @param  array   $data
     * @return Builder
     */
    public function scopeUserUpdatable(Builder $query, $data = []): Builder
    {
        $user = auth()->user();
        return $query->whereHas('party', function($q) use ($user) {
            return $q->where('user_id', $user->id);
        });
    }

    /**
     * A user can delete a party address if they own it.
     *
     * @param  Builder $query
     * @param  array   $data
     * @return Builder
     */
    public function scopeUserDeletable(Builder $query, $data = []): Builder
    {
        $user = auth()->user();
        return $query->whereHas('party', function($q) use ($user) {
            return $q->where('user_id', $user->id);
        });
    }
}
