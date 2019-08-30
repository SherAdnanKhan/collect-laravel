<?php

namespace App\Observers;

use App\Models\Collaborator;
use App\Models\CollaboratorPermission;
use App\Models\Project;
use App\Models\Song;
use Illuminate\Support\Facades\Log;
use Nuwave\Lighthouse\Execution\Utils\Subscription;

class CollaboratorObserver
{
    /**
     * Handle the folder "created" event.
     *
     * @param  \App\Models\Collaborator  $collaborator
     * @return void
     */
    public function created(Collaborator $collaborator)
    {
        // The default permissions
        $permissions = collect(CollaboratorPermission::TYPES)->map(function($type) {
            return new CollaboratorPermission(['type' => $type, 'level' => 'read']);
        })->all();

        // The default permissions if we've got a recording collaborator.
        if (!is_null($collaborator->recording_id)) {
            $permissions = [
                new CollaboratorPermission(['type' => 'recording', 'level' => 'read']),
                new CollaboratorPermission(['type' => 'project', 'level' => 'read']),
            ];
        }

        // Setup the default permissions.
        $collaborator->permissions()->saveMany($permissions);

        $collaborator->createAndSendInvite();

        Subscription::broadcast('collaboratorCreated', $collaborator);
    }

    /**
     * Handle the folder "deleted" event.
     *
     * @param  \App\Models\Collaborator  $collaborator
     * @return void
     */
    public function deleted(Collaborator $collaborator)
    {
        Subscription::broadcast('collaboratorRemoved', $collaborator);
    }
}
