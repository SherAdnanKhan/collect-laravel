<?php

namespace App\Models;

use App\Models\Session;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserPluginCode extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'session_id', 'type', 'code', 'expires_at'
    ];

    protected $timestamps = false;

    /**
     * Get the owning user of this model.
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the owning Session of this model.
     *
     * @return BelongsTo
     */
    public function session()
    {
        return $this->belongsTo(Session::class);
    }
}
