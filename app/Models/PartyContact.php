<?php

namespace App\Models;

use App\Models\Party;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Represent a parties contact details,
 * either a phone number or an email
 */
class PartyContact extends Model
{
    protected $fillable = [
        'party_id', 'type', 'value', 'primary'
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
}
