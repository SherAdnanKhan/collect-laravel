<?php

namespace App\Util\BuilderQueries;

use Illuminate\Foundation\Auth\User;
use Illuminate\Database\Eloquent\Builder;

/**
 * This will append to a query, clauses which will check to see if
 * the user has access to a recording via a collaborator row on it.
 */
class CollaboratorRecordingAccess
{
    private $query;
    private $user;

    /**
     * Create a new query extension to scope for
     * recording access.
     *
     * @param Builder  $query
     * @param User     $user
     */
    public function __construct(Builder $query, User $user)
    {
        $this->query = $query;
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

        return $this->query->whereHas('collaborators', function($q) use ($user) {
            return $q->select(['collaborators.user_id', 'collaborators.accepted'])
                ->where('collaborators.accepted', true)
                ->where('collaborators.user_id', $user->getAuthIdentifier())
                ->whereHas('permissions', function($q) {
                    return $q->select(['collaborator_permissions.level', 'collaborator_permissions.type'])
                        ->whereIn('collaborator_permissions.level', ['read'])
                        ->whereIn('collaborator_permissions.type', ['recording']);
                });
        });
    }
}
