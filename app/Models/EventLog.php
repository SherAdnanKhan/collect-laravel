<?php

namespace App\Models;

use App\Contracts\UserAccessible;
use App\Models\Collaborators;
use App\Models\Project;
use App\Models\User;
use App\Traits\OrderScopes;
use App\Traits\UserAccesses;
use App\Util\BuilderQueries\ProjectAccess;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Represents tracked events and actions on a resource
 * performed by a user.
 */
class EventLog extends Model implements UserAccessible
{
    use UserAccesses;
    use OrderScopes;

    const UNREAD_CACHE_KEY_FORMAT = 'event-logs.unread-count.%s.%s';
    const LAST_READ_CACHE_KEY_FORMAT = 'event-logs.last-read-at.%s.%s';

    const TYPES = [
        'project',
        'recording',
        'session',
        'collaborator',
        'comment',
        'folder',
    ];

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

    /**
     * Determine when a user can see the event log items.
     *
     * @param  Builder $query
     * @param  array   $data
     * @return Builder
     */
    public function scopeUserViewable(Builder $query, $data = []): Builder
    {
        $user = auth()->user();

        return $this->wrapUserRelationCheck(
            $user,
            (new ProjectAccess($query, $user, ['project'], ['read']))->getQuery()
        );
    }
}


// TODO: staging.ERROR: Argument 2 passed to App\Util\BuilderQueries\ProjectAccess::__construct() must be an instance of Illuminate\Foundation\Auth\User, null given, called in /home/forge/veva-studio-collect.analogrepublic.com/app/Models/EventLog.php on line 91 {"exception":"[object] (Symfony\\Component\\Debug\\Exception\\FatalThrowableError(code: 0): Argument 2 passed to App\\Util\\BuilderQueries\\ProjectAccess::__construct() must be an instance of Illuminate\\Foundation\\Auth\\User, null given, called in /home/forge/veva-studio-collect.analogrepublic.com/app/Models/EventLog.php on line 91 at /home/forge/veva-studio-collect.analogrepublic.com/app/Util/BuilderQueries/ProjectAccess.php:32)
