<?php

namespace App\Models;

use App\Models\Party;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

/**
 * Represent a parties addresses.
 */
class PartyAddress extends Model
{
    protected $fillable = [
        'party_id', 'line_1', 'line_2', 'line_3',
        'city', 'district', 'postal_code', 'territory_code',
        'territory_code_type',
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
}
