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
        $query = $project->newQuery()->select('projects.id')
            ->where('projects.id', $project->id)
            ->where(function($q) use ($user) {
                return (new CollaboratorPermission($q, $user, ['collaborator'], ['create']))
                    ->getQuery()
                    ->orWhere('projects.user_id', $user->getAuthIdentifier());
            });

        if ($userToAdd instanceof User) {
            $query = $query->whereDoesntHave('collaborators', function($q) use ($userToAdd) {
                return $q->where('user_id', $userToAdd->id);
            });
        }

        return $query->exists();
    }
}
