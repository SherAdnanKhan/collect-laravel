<?php

namespace App\Policies;

use App\Models\User;

class RecordingPolicy
{
    /**
     * Define a create policy on a recording.
     *
     * @param  User $user
     * @return bool
     */
    public function create(User $user)
    {
        // TODO:
        // - A user can only create a recording if they have create
        // - permissions on recordings on a project.
        return true;
    }
}
