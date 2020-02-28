<?php

namespace App\Observers;

use App\Jobs\Emails\SendUserStorageLimitReachedEmail;
use App\Models\File;
use Illuminate\Support\Facades\DB;
use Nuwave\Lighthouse\Execution\Utils\Subscription;

class FileObserver
{
    /**
     * Handle the folder "created" event.
     *
     * @param  \App\Models\File  $file
     * @return void
     */
    public function created(File $file)
    {
        if (!$file->size) {
            return;
        }

        $project = null;
        if ($file->project_id) {
            $project = $file->project;
            $user = $project->user;
        } else {
            $user = $file->user;
        }

        if ($project) {
            $project->update([
                'total_storage_used' => DB::raw('total_storage_used + ' . (int)$file->size)
            ]);
        }

        $user->update([
            'total_storage_used' => DB::raw('total_storage_used + ' . (int)$file->size)
        ]);

        Subscription::broadcast('userStorageUpdated', $user);

        if (!$user->hasStorageSpaceAvailable()) {
            // If the user no longer has enough storage left, then we need to email them.
            SendUserStorageLimitReachedEmail::dispatch($user);
        }
    }
}
