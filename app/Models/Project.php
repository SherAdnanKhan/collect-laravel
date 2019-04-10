<?php

namespace App\Models;

use App\Contracts\Creditable;
use App\Contracts\EventLoggable;
use App\Contracts\UserAccessible;
use App\Models\Collaborator;
use App\Models\CollaboratorInvite;
use App\Models\Comment;
use App\Models\Credit;
use App\Models\Folder;
use App\Models\Party;
use App\Models\Session;
use App\Models\Song;
use App\Models\SongRecording;
use App\Models\User;
use App\Models\UserFavourite;
use App\Traits\EventLogged;
use App\Traits\OrderScopes;
use App\Traits\UserAccesses;
use App\Util\BuilderQueries\CollaboratorPermission;
use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model implements UserAccessible, EventLoggable, Creditable
{
    use EventLogged;
    use UserAccesses;
    use OrderScopes;
    use SoftDeletes;
    use SoftCascadeTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'name', 'description', 'main_artist_id',
        'number', 'label_id', 'image', 'total_storage_used'
    ];

    /**
     * The relationships that will also be
     * soft deleted when this resource is.
     *
     * @var array
     */
    protected $softCascade = [
        'folders', 'files', 'recordings',
        'sessions', 'credits'
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
     * Get the credits for this project
     *
     * @return BelongsToMany
     */
    public function credits(): BelongsToMany
    {
        return $this->belongsToMany(Credit::class, 'credits_to_projects');
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

    public function artist(): HasOne
    {
        return $this->hasOne(Party::class, 'id', 'main_artist_id');
    }

    public function label(): HasOne
    {
        return $this->hasOne(Party::class, 'id', 'label_id');
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
    public function scopeUserViewable(Builder $query, $data = []): Builder
    {
        $user = $this->getUser($data);

        // Add to the query a check to see if the user
        // has read permission on the project, or owns it.
        return (new CollaboratorPermission($query, $user, ['project'], ['read']))
            ->getQuery()
            ->orWhere('user_id', $user->getAuthIdentifier());
    }

    /**
     * A scope to filter projects who're updatable by the
     * current authed user.
     *
     * @param  Builder $query
     * @param  Model   $model
     * @return Builder
     */
    public function scopeUserUpdatable(Builder $query, $data = []): Builder
    {
        $user = $this->getUser($data);

        // Add to the query a check to see if the user
        // has read permission on the project, or owns it.
        return (new CollaboratorPermission($query, $user, ['project'], ['update']))
            ->getQuery()
            ->orWhere('user_id', $user->getAuthIdentifier());
    }

    /**
     * A scope to filter projects who're deletable by the
     * current authed user.
     *
     * @param  Builder $query
     * @param  Model   $model
     * @return Builder
     */
    public function scopeUserDeletable(Builder $query, $data = []): Builder
    {
        $user = $this->getUser($data);

        // Only the person who owns the project can delete it.
        return $query->where('user_id', $user->getAuthIdentifier());
    }

    /**
     * When logging events fot the project, we'll say this
     * project is the project.
     *
     * @return Project
     */
    public function getProject()
    {
        return $this;
    }

    /**
     * Grab a boolean result of a query which we'll use to check permissions on
     * resources from this project for the use in policies.
     *
     * @param  Authenticatable $user
     * @param  array           $types
     * @param  array           $permissions
     * @param  function        $hook
     * @return bool
     */
    public function userPolicy(Authenticatable $user, $types = ['project'], $permissions = ['read'], $hook = null): bool
    {
        $query = $this->newQuery()->select('projects.id')
            ->where('projects.id', $this->getKey())
            ->where(function($q) use ($user, $types, $permissions) {
                return (new CollaboratorPermission($q, $user, $types, $permissions))
                    ->getQuery()
                    ->orWhere('projects.user_id', $user->getKey());
            });

        if (!is_null($hook) && is_callable($hook)) {
            $query = $hook($query);
        }

        return $query->exists();
    }

    public function getContributorRoleType(): string
    {
        return 'project';
    }

    public function getContributorReferenceKey(): string
    {
        return 'ProjectContributorReference';
    }
}
