<?php

namespace App\Models;

use App\Models\Collaborators;
use App\Models\Comment;
use App\Models\Session;
use App\Models\User;
use App\Models\UserFavourite;
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

    /**
     * Get the collaborator users for this project.
     *
     * @return Collection
     */
    public function collaborators()
    {
        return $this->hasMany(Collaborators::class);
    }

    /**
     * Get the files belonging to this project.
     *
     * @return Collection
     */
    public function files()
    {
        return $this->hasMany(File::class);
    }

    /**
     * Get this recordings recordings.
     *
     * @return Collection
     */
    public function recordings()
    {
        return $this->hasMany(Recording::class);
    }

    /**
     * Get this sessions recordings.
     *
     * @return Collection
     */
    public function sessions()
    {
        return $this->hasMany(Session::class);
    }

    /**
     * Get the projects favourite row.
     */
    public function favourites()
    {
        return $this->morphMany(UserFavourite::class, 'favoured');
    }

    /**
     * Get all of the projects' comments.
     *
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
