<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'name', 'description',
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

    // public function songs()
    // {
    //     return $this->hasMany(Song::class);
    // }

    // public function files()
    // {
    //     return $this->hasMany(File::class);
    // }
}
