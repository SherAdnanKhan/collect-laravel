<?php

namespace App\Models;

use App\Models\Administrator;
use Illuminate\Database\Eloquent\Model;

class AdministratorTwoFactorToken extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'administrator_id', 'token', 'expires_at'
    ];

    protected $timestamps = false;

    /**
     * Get the owning administrator of this model.
     *
     * @return BelongsTo
     */
    public function administrator()
    {
        return $this->belongsTo(Administrator::class);
    }
}
