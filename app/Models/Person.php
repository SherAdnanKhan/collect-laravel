<?php

namespace App\Models;

use App\Models\Collaborators;
use App\Models\PersonSession;
use App\Models\Session;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $table = 'persons';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'name', 'email',
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

    /**
     * Get all sessions this person is associated to.
     *
     * @return BelongsToMany
     */
    public function sessions()
    {
        return $this->belongsToMany(Session::class, 'persons_to_sessions')
            ->using(PersonSession::class);
    }
}
