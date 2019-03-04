<?php

namespace App\Models;

use App\Contracts\UserAccessible;
use App\Models\Collaborator;
use App\Models\CollaboratorInvite;
use App\Models\Comment;
use App\Models\Credit;
use App\Models\Folder;
use App\Models\Session;
use App\Models\Song;
use App\Models\SongRecording;
use App\Models\User;
use App\Models\UserFavourite;
use App\Traits\UserAccesses;
use App\Util\BuilderQueries\CollaboratorPermission;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Project extends Model implements UserAccessible
{
    use UserAccesses;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'name', 'description', 'artist',
        'number', 'label', 'image',
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
     * Get the files belonging to this project.
     *
     * @return Integer
     */
    public function getCollaboratorCountAttribute(): int
    {
        return $this->hasMany(Collaborator::class)->count();
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
     * Get the files belonging to this project.
     *
     * @return Integer
     */
    public function getFileCountAttribute()
    {
        return $this->hasMany(File::class)->count();
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
     * Get songs which have been a part of a recording
     * under this project.
     *
     * @return BelongsToMany
     */
    public function songs(): BelongsToMany
    {
        return $this->belongsToMany(Song::class, 'songs_to_recordings')->using(SongRecording::class);
    }

    /**
     * Get the files belonging to this project.
     *
     * @return Integer
     */
    public function getRecordingCountAttribute(): int
    {
        return $this->hasMany(Recording::class)->count();
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
     * Get the files belonging to this project.
     *
     * @return Integer
     */
    public function getSessionCountAttribute()
    {
        return $this->hasMany(Session::class)->count();
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

    /**
     * Grab the credits/contributions directly on this resource.
     *
     * @return MorphMany
     */
    public function credits(): MorphMany
    {
        return $this->morphMany(Credit::class, 'contribution');
    }

    /**
     * Get the path to this projects root files
     *
     * @return string
     */
    public function getUploadFolderPath()
    {
        return md5($this->attributes['id']);
    }

    /**
     * A scope to filter projects who're accessible by the
     * current authed user.
     *
     * @param  Builder $query
     * @param  Model   $model
     * @return Builder
     */
    public function scopeUserViewable(Builder $query, $data): Builder
    {
        $user = auth()->user();

        // Add to the query a check to see if the user
        // has read permission on the project, or owns it.
        return (new CollaboratorPermission($query, $user, ['read']))
            ->getQuery()
            ->orWhere('user_id', $user->getAuthIdentifier());
    }
}
