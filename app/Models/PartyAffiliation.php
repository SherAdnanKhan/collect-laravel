<?php

namespace App\Models;

use App\Contracts\UserAccessible;
use App\Traits\UserAccesses;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Represent a parties affiliation.
 */
class PartyAffiliation extends Model implements UserAccessible
{
    use UserAccesses;

    protected $table = 'party_user_affiliations';

    protected $fillable = [
        'party_id', 'user_affiliation_id'
    ];

    /**
     * The party with which this affiliation belongs.
     *
     * @return BelongsTo
     */
    public function party(): BelongsTo
    {
        return $this->belongsTo(Party::class,'party_id');
    }

    /**
     * The user affiliation for this party.
     *
     * @return BelongsTo
     */
    public function userAffiliation(): BelongsTo
    {
        return $this->belongsTo(UserAffiliation::class,'user_affiliation_id');
    }

    /**
     * A user can delete a party affiliation if they own it.
     *
     * @param Builder $query
     * @param array $data
     * @return Builder
     */
    public function scopeUserDeletable(Builder $query, $data = []): Builder
    {
        $user = auth()->user();
        return $query->whereHas('party', function ($q) use ($user) {
            return $q->where('user_id', $user->id);
        });
    }
}
