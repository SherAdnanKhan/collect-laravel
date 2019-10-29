<?php

namespace App\Policies;

use App\Models\Folder;
use App\Models\Project;
use App\Models\Recording;
use App\Models\User;
use App\Util\BuilderQueries\CollaboratorPermission;
use App\Util\BuilderQueries\CollaboratorRecordingAccess;

class FolderPolicy
{
    /**
     * Define a create policy on a session.
     *
     * @param  User $user
     * @param  Project $project
     * @param  Folder $rootFolder | null
     * @return bool
     */
    public function create(User $user, Project $project, ?Folder $rootFolder)
    {
        $createPermission = $project->userPolicy($user, ['file'], ['create']);
        if ($createPermission || !$rootFolder || $rootFolder->recording()->count() < 1) {
            return $createPermission;
        }

        return Recording::where('id', optional($rootFolder->recording)->id)->where(function($q) use ($user) {
            return (new CollaboratorRecordingAccess($q, $user))->getQuery();
        })->count();
    }

    /**
     * Define a update policy on a session.
     *
     * @param  User $user
     * @param  Project $project
     * @return bool
     */
    public function update(User $user, Project $project, Folder $folder)
    {
        $updatePermission = $project->userPolicy($user, ['file'], ['update']);
        $rootFolder = ($folder->root_folder_id ? $folder->rootFolder : $folder);
        if ($updatePermission || !$rootFolder || $rootFolder->recording()->count() < 1) {
            return $updatePermission;
        }

        return Recording::where('id', optional($rootFolder->recording)->id)->where(function($q) use ($user) {
            return (new CollaboratorRecordingAccess($q, $user))->getQuery();
        })->count();
    }
}
