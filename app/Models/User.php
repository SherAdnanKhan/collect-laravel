<?php

namespace App\Models;

use App\Models\Collaborators;
use App\Models\File;
use App\Models\Project;
use App\Models\UserFavourite;
use App\Models\UserProfile;
use App\Models\UserTwoFactorToken;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;

class User extends Authenticatable
{
    use Billable;
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
     * @return Collection
     */
    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    /**
     * The two factor tokens for the user.
     *
     * @return Collection
     */
    public function tokens()
    {
        return $this->hasMany(UserTwoFactorToken::class);
    }

    /**
     * The users favourite items in the system.
     *
     * @return Collection
     */
    public function favourites()
    {
        return $this->hasMany(UserFavourite::class);
    }

    /**
     * All of the users projects.
     *
     * @return Collection
     */
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Get the times this user has been a collaborator
     *
     * @return Collection
     */
    public function collaborators()
    {
        return $this->hasMany(Collaborators::class);
    }

    /**
     * Get the people on this users accounts.
     *
     * @return Collection
     */
    public function persons()
    {
        return $this->hasMany(Person::class);
    }

    /**
     * Get the files belonging to this user.
     *
     * @return Collection
     */
    public function files()
    {
        return $this->hasMany(File::class);
    }
}
