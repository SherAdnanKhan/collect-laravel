<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use App\Util\BuilderQueries\CollaboratorPermission;

class SessionPolicy
{
    /**
     * Define a create policy on a session.
     *
     * @param  User $user
     * @param  Project $project
     * @return bool
     */
    public function create(User $user, Project $project)
    {
        return $project->userPolicy($user, ['session'], ['create']);
    }
}
