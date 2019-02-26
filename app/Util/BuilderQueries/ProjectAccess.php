<?php

namespace App\Util\BuilderQueries;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User;

/**
 * Extend the query to provide scoping on auth'd user
 * access to relational project data. It will include
 * clauses to check users access via their access
 * to a project, by ownership or collaborator permission.
 */
class ProjectAccess
{
    private $query;
    private $permissions;
    private $user;

    /**
     * Create a new query extension to scope for
     * project access by passing in the query and the permissions allowed.
     *
     * @param Builder   $query
     * @param User      $user
     * @param array     $permissions
     */
    public function __construct(Builder $query, User $user, $permissions = ['read'])
    {
        $this->query = $query;
        $this->permissions = $permissions;
        $this->user = $user;
    }

    /**
     * Get the build query to filter down by project accesss.
     *
     * @return Builder
     */
    public function getQuery(): Builder
    {
        $user = $this->user;
        $permissions = $this->permissions;

        return $this->query->whereHas('project', function($q) use ($user, $permissions) {
            return (new CollaboratorPermission($q, $user, $permissions))
                ->getQuery()->orWhere('user_id', $user->getAuthIdentifier());
        });
    }
}
