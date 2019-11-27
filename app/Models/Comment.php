<?php

namespace App\Models;

use App\Contracts\EventLoggable;
use App\Contracts\UserAccessible;
use App\Models\Project;
use App\Models\User;
use App\Traits\EventLogged;
use App\Traits\OrderScopes;
use App\Traits\UserAccesses;
use App\Util\BuilderQueries\ProjectAccess;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Represent a comment in a project on a resource.
 */
class Comment extends Model implements UserAccessible, EventLoggable
{
    use EventLogged;
    use UserAccesses;
    use OrderScopes;

    protected $fillable = [
        'project_id', 'user_id', 'resource_type', 'resource_id', 'message',
    ];

    /**
     * Get the project this comment is on.
     *
     * @return BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the user this comment was posted by.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the resource the the user commented on,
     * this can be a nullable resource in the case that
     * the user comments on the project directly.
     *
     * @return MorphTo
     */
    public function commentable(): MorphTo
    {
        return $this->morphTo(null, 'resource_type', 'resource_id');
    }

    /**
     * We're filtering update by read access to the project.
     *
     * @param  Builder $query
     * @param  array   $data
     * @return Builder
     */
    public function scopeUserUpdatable(Builder $query, $data = []): Builder
    {
        $user = $this->getUser($data);

        return $this->wrapUserRelationCheck(
            $user,
            (new ProjectAccess($query, $user, [$this->getTypeName()], ['read']))->getQuery()
        );
    }

    /**
     * Determine how we filter out comments which can be
     * deleted by the currently auth'd user.
     *
     * @param  Builder $query
     * @param  array   $data
     * @return Builder
     */
    public function scopeUserDeletable(Builder $query, $data = []): Builder
    {
        // Only the user who wrote the comment can
        // delete their comment.
        return $query->where('user_id', auth()->user()->getAuthIdentifier());
    }

    public function getIdentifier(): string
    {
        return $this->message;
    }

    /**
     * The type we're identifying as when we're checking
     * for user access to this resource.
     */
    public function getTypeName(): string
    {
        // We treat it as a project because it doesn't
        // have it's own permissions.
        return 'project';
    }
}
