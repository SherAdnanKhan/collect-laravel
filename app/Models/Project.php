<?php

namespace App\Models;

use App\Models\Collaborator;
use App\Models\CollaboratorInvite;
use App\Models\Comment;
use App\Models\Folder;
use App\Models\Session;
use App\Models\User;
use App\Models\UserFavourite;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

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
     * The user owner for this project.
     *
     * @return User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the collaborator users for this project.
     *
     * @return Collection
     */
    public function collaborators(): HasMany
    {
        return $this->hasMany(Collaborator::class);
    }

    /**
     * Get all folders in this project.
     *
     * @return HasMany
     */
    public function folders(): HasMany
    {
        return $this->hasMany(Folder::class);
    }

    /**
     * Get the files belonging to this project.
     *
     * @return HasMany
     */
    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }

    /**
     * Get this recordings recordings.
     *
     * @return HasMany
     */
    public function recordings(): HasMany
    {
        return $this->hasMany(Recording::class);
    }

    /**
     * Get this sessions recordings.
     *
     * @return HasMany
     */
    public function sessions(): HasMany
    {
        return $this->hasMany(Session::class);
    }

    /**
     * Get the projects favourite row.
     *
     * @return MorphMany
     */
    public function favourites(): MorphMany
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

    /**
     * Get the projects invites for new collaborators.
     *
     * @return HasMany
     */
    public function collaboratorInvites(): HasMany
    {
        return $this->hasMany(CollaboratorInvite::class);
    }
}
