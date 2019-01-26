<?php

namespace App\Models;

use App\Models\Collaborators;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Persons extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'name', 'email',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * The user owner for this project.
     *
     * @return User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
