<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use App\Util\BuilderQueries\CollaboratorPermission;

class RecordingPolicy
{
    /**
     * Define a create policy on a recording.
     *
     * @param  User $user
     * @param  Project $project
     * @return bool
     */
    public function create(User $user, Project $project)
    {
        $query = $project->newQuery();
        return $query->select('projects.id')
            ->where('projects.id', $project->id)
            ->where(function($q) use ($user) {
                return (new CollaboratorPermission($q, $user, ['recording'], ['create']))
                    ->getQuery()
                    ->orWhere('projects.user_id', $user->getAuthIdentifier());
            })->exists();
    }
}
