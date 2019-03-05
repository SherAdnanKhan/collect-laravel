<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Application extends Authenticatable
{
    protected $guard = 'token';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'auth_token',
    ];
}
