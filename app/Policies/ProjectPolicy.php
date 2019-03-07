<?php

namespace App\Policies;

use App\Models\User;

class ProjectPolicy
{
    /**
     * Define a create policy on a project.
     *
     * @param  User $user
     * @return bool
     */
    public function create(User $user)
    {
        // TODO:
        // - A user can only create a project if they have a valid
        // subscription?
        return true;
    }
}
