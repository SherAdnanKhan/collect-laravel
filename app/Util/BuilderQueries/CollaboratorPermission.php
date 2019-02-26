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

    /**
     * Create a new query extension to scope for
     * project access by passing in the query and the permissions allowed.
     *
     * @param Builder  $query
     * @param User     $user
     * @param array    $permissions
     */
    public function __construct(Builder $query, User $user, $permissions = ['read'])
    {
        $this->query = $query;
        $this->permissions = $permissions;
        $this->user = $user;
    }

    /**
     * Get the build query to filter down by collaborator permission.
     *
     * @return Builder
     */
    public function getQuery(): Builder
    {
        $user = $this->user;
        $permissions = $this->permissions;

        return $this->query->whereHas('collaborators', function($q) use ($user, $permissions) {
            return $q->where('user_id', $user->getAuthIdentifier())->whereHas('permissions', function($q) use ($permissions) {
                return $q->whereIn('level', $permissions);
            });
        });
    }
}
