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
        return $project->userPolicy($user, ['recording'], ['create']);
    }
}
