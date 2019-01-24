<?php

namespace App\Models;

use App\Models\UserProfile;
use App\Models\UserTwoFactorToken;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $guard = 'api';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The profile associated to the user.
     *
     * @return HasOne
     */
    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    /**
     * The two factor tokens for the user.
     *
     * @return HasMany
     */
    public function twoFactorTokens()
    {
        return $this->hasMany(UserTwoFactorToken::class);
    }
}
