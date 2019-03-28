<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use App\Util\BuilderQueries\CollaboratorPermission;

class CommentPolicy
{
    /**
     * Define a create policy on a comment.
     *
     * @param  User $user
     * @param  Project $project
     * @return bool
     */
    public function create(User $user, Project $project)
    {
        return $project->userPolicy($user, ['project'], ['read']);
    }
}
