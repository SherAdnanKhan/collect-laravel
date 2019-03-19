<?php

namespace App\Models;

use App\Models\Credit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CreditRole extends Model
{
    /**
     * Mass assignable fields.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'name',
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
