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
        if ($collaborator->type === 'recording') {
            $permissions = self::defaultRecordingPermissions();
        }

        // Setup the default permissions.
        $collaborator->permissions()->saveMany($permissions);

        $collaborator->createAndSendInvite();

        // Subscription::broadcast('collaboratorCreated', $collaborator);
        Subscription::broadcast('userPermissionsUpdated', $collaborator->user);
    }

    /**
     * Handle the folder "updated" event.
     *
     * @param  \App\Models\Collaborator  $collaborator
     * @return void
     */
    public function updated(Collaborator $collaborator)
    {
        // If we've updated the type, we need to make sure we're resetting the
        // default permissions for that type.
        if ($collaborator->isDirty('type')) {
            $collaborator->permissions()->each(function($permission) {
                $permission->delete();
            });

            // The default permissions
            $permissions = collect(CollaboratorPermission::TYPES)->map(function($type) {
                return new CollaboratorPermission(['type' => $type, 'level' => 'read']);
            })->all();

            // The default permissions if we've got a recording collaborator.
            if ($collaborator->type === 'recording') {
                $permissions = self::defaultRecordingPermissions();
            }

            // Setup the default permissions.
            $collaborator->permissions()->saveMany($permissions);

            // Broadcast a GraphQL subscription for clients.
            Subscription::broadcast('userPermissionsUpdated', $collaborator->user);
        }
    }

    /**
     * Handle the folder "deleted" event.
     *
     * @param  \App\Models\Collaborator  $collaborator
     * @return void
     */
    public function deleted(Collaborator $collaborator)
    {
        // Subscription::broadcast('collaboratorRemoved', $collaborator);
    }

    private static function defaultRecordingPermissions()
    {
        return [
            new CollaboratorPermission(['type' => 'recording', 'level' => 'read']),
            new CollaboratorPermission(['type' => 'recording', 'level' => 'update']),
            new CollaboratorPermission(['type' => 'project', 'level' => 'read']),
            new CollaboratorPermission(['type' => 'session', 'level' => 'read']),
            new CollaboratorPermission(['type' => 'session', 'level' => 'create']),
            new CollaboratorPermission(['type' => 'session', 'level' => 'update']),
        ];
    }
}
