<?php

namespace App\Models;

use App\Contracts\UserAccessible;
use App\Models\Project;
use App\Models\User;
use App\Traits\UserAccesses;
use App\Util\BuilderQueries\ProjectAccess;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Represent a comment in a project on a resource.
 */
class Comment extends Model implements UserAccessible
{
    use UserAccesses;

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
     * A scope to filter comments to only those viewable
     * by the user if they have access to the project.
     *
     * @param  Builder $query
     * @param  Model   $model
     * @return Builder
     */
    public function scopeUserViewable(Builder $query, $data = []): Builder
    {
        $user = auth()->user();

        // Check to see if the current user owns or
        // has read access as a collaborator on the project
        // with which this is on.
        return (new ProjectAccess($query, $user))
            ->getQuery()
            ->orWhere('user_id', $user->getAuthIdentifier());
    }
}
