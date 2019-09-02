<?php

namespace App\Models;

use App\Contracts\UserAccessible;
use App\Models\Party;
use App\Traits\UserAccesses;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Represent a parties contact details,
 * either a phone number or an email
 */
class PartyContact extends Model implements UserAccessible
{

    use UserAccesses;

    protected $fillable = [
        'party_id', 'name', 'type', 'value', 'primary'
    ];

    /**
     * The party with which this contact belongs.
     *
     * @return BelongsTo
     */
    public function party(): BelongsTo
    {
        return $this->belongsTo(Party::class);
    }

    public function scopeEmailsOnly(Builder $query, $args = []): Builder
    {
        return $query->where('type', 'email');
    }

    public function scopePhonesOnly(Builder $query, $args = []): Builder
    {
        return $query->where('type', 'phone');
    }

    /**
     * A party contact is viewable to everyone.
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
     * A user can update a party contact if they own it.
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
     * A user can delete a party contact if they own it.
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
