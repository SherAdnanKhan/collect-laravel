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
    private $types;
    private $permissions;
    private $user;
    private $relation;

    /**
     * Create a new query extension to scope for
     * project access by passing in the query and the permissions allowed.
     *
     * @param Builder   $query
     * @param User      $user
     * @param array     $types
     * @param array     $permissions
     * @param string    $relation
     */
    public function __construct(Builder $query, User $user, $types = ['project'], $permissions = ['read'], $relation = 'project')
    {
        $this->query = $query;
        $this->types = $types;
        $this->permissions = $permissions;
        $this->user = $user;
        $this->relation = $relation;
    }

    /**
     * Get the build query to filter down by project accesss.
     *
     * @return Builder
     */
    public function getQuery(): Builder
    {
        $user = $this->user;
        $types = $this->types;
        $permissions = $this->permissions;

        return $this->query->whereHas($this->relation, function($q) use ($user, $types, $permissions) {
            return (new CollaboratorPermission($q, $user, $types, $permissions))
                ->getQuery()
                ->select('user_id')
                ->orWhere('user_id', $user->getAuthIdentifier());
        });
    }
}
