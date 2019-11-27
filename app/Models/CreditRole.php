<?php

namespace App\Models;

use App\Models\Credit;
use App\Traits\OrderScopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CreditRole extends Model
{
    use OrderScopes;

    const TYPES_WITH_LABELS = [
        'ResourceContributorRole' => 'Resource Contributor Role',
        'CreativeContributorRole' => 'Creative Contributor Role',
        'BusinessContributorRole' => 'Business Contributor Role',
        'NewStudioRole'           => 'New Studio Role',
        'ArtistRole'              => 'Artist Role',
    ];

    /**
     * Mass assignable fields.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'name', 'ddex_key', 'user_defined'
    ];

    /**
     * We don't use timestamps on this one.
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * All credits which use this role.
     *
     * @return HasMany
     */
    public function credits(): HasMany
    {
        return $this->hasMany(Credit::class);
    }
}
