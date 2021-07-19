<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Affiliation extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'affiliation_type', 'name', 'slug', 'url'
    ];

    protected $hidden = [
        'public_key',
        'secret_key'
    ];

    protected $table= 'affiliations';

    /**
     * Get the owning user of this model.
     *
     * @return BelongsTo
     */
    public function userAffiliation(): BelongsTo
    {
        return $this->belongsTo(UserAffiliation::class);
    }
}
