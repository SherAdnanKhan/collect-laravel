<?php

namespace App\Models;

use App\Models\Collaborators;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Represents tracked events and actions on a resource
 * performed by a user.
 */
class EventLog extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'project_id', 'resource_id', 'resource_type',
        'action', 'message',
    ];

    /**
     * Grab the project on which this action was performed.
     *
     * @return BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Grab the user who performed the action.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Grab the resource which the action happened on.
     *
     * @return MorphTo
     */
    public function resource(): MorphTo
    {
        return $this->morphTo(null, 'resource_type', 'resource_id');
    }
}
