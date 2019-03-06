<?php

namespace App\Util\BuilderQueries;

use Illuminate\Foundation\Auth\User;
use Illuminate\Database\Eloquent\Builder;

/**
 * It will query to add clauses clauses to check users access via their access
 * to a project, by collaborator permission.
 */
class CollaboratorPermission
{
    private $query;
    private $permissions;
    private $user;
    private $types;

    /**
     * Create a new query extension to scope for
     * project access by passing in the query, the
     * type of resource and the permissions allowed.
     *
     * @param Builder  $query
     * @param User     $user
     * @param array    $type
     * @param array    $permissions
     */
    public function __construct(Builder $query, User $user, $types = ['project'], $permissions = ['read'])
    {
        $this->query = $query;
        $this->permissions = $permissions;
        $this->user = $user;
        $this->types = $types;
    }

    /**
     * Get the build query to filter down by collaborator permission.
     *
     * @return Builder
     */
    public function getQuery(): Builder
    {
        $user = $this->user;
        $types = $this->types;
        $permissions = $this->permissions;

        return $this->query->whereHas('collaborators', function($q) use ($user, $types, $permissions) {
            return $q->select('user_id')
                ->where('user_id', $user->getAuthIdentifier())
                ->whereHas('permissions', function($q) use ($types, $permissions) {
                    return $q->select(['level', 'type'])
                        ->whereIn('level', $permissions)
                        ->whereIn('type', $types);
                });
        });
    }
}
