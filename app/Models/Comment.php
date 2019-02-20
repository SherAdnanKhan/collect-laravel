<?php

namespace App\Models;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Represent a comment in a project on a resource.
 */
class Comment extends Model
{
    protected $fillable = [
        'project_id', 'user_id', 'resource_type', 'resource_id', 'message',
    ];

    /**
     * Get the project this comment is on.
     *
     * @return BelongsTo
     */
    public function project() : BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the user this comment was posted by.
     *
     * @return BelongsTo
     */
    public function user() : BelongsTo
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
    public function commentedOn() : MorphTo
    {
        return $this->morphTo(null, 'resource_type', 'resource_id');
    }
}
