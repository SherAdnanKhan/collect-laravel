<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use App\Util\BuilderQueries\CollaboratorPermission;

class CollaboratorPolicy
{
    /**
     * Define a create policy on a comment.
     *
     * @param  User $user
     * @param  Project $project
     * @param  User|null $userToAdd
     * @return bool
     */
    public function create(User $user, Project $project, $userToAdd = null)
    {
        return $project->userPolicy($user, ['collaborator'], ['create'], function($query) use ($userToAdd) {
            if ($userToAdd instanceof User) {
                return $query->whereDoesntHave('collaborators', function($q) use ($userToAdd) {
                    return $q->where('user_id', $userToAdd->getKey());
                });
            }

            return $query;
        });
    }
}
