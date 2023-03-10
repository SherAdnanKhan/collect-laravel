<?php

namespace App\Jobs;

use App\Jobs\Emails\SendUserStorageLimitReachedEmail;
use App\Models\Project;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Nuwave\Lighthouse\Execution\Utils\Subscription;

/**
 * Calculates the users total storage used in bytes.
 */
class UpdateUserTotalStorageUsed implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $cacheKey = 'UpdateUserTotalStorageUsed-lastran';

        // Grab last ran, default 0 if it doesn't exist
        $lastRan = Cache::get($cacheKey, 0);

        // Then put the current time in the cache for 30 minutes.
        Cache::put($cacheKey, time(), 30);

        // Grab projects which have files that have been updated or deleted
        // since we last ran this job. And eager load all the users, so we can
        // grab them all in a single query.
        $projects = Project::select('projects.id', 'projects.user_id')
            ->whereHas('allFilesNoScope', function($query) use ($lastRan) {
                return $query->select('files.project_id', 'files.updated_at', 'files.deleted_at')
                    ->where('files.deleted_at', '>=', date("Y-m-d H:i:s", $lastRan))
                    ->orWhere('files.updated_at', '>=', date("Y-m-d H:i:s", $lastRan));
            })
            ->groupBy('projects.id')
            ->with('user:id,first_name,last_name')
            ->with(['user.subscriptions' => function($query) {
                return $query->select('stripe_plan');
            }])
            ->get();

        // Array to keep track of which users nee to have
        // the storage recalculated.
        $usersToUpdate = [];

        // We go over each project and grab a sum of the non-deleted files sizes.
        foreach ($projects as $project) {
            $totalStorageUsed = $project->allFiles()
                ->whereNull('deleted_at')
                ->sum('size');

            // Run an update to save that value.
            $project->total_storage_used = $totalStorageUsed;
            $saved = $project->save();

            // If it's saved and we already haven't marked a user to update, do so.
            if ($saved && !array_key_exists($project->user->id, $usersToUpdate)) {
                $usersToUpdate[$project->user->id] = $project->user;
            }
        }

        $fileUsers = User::select('users.id')
            ->whereHas('filesNoScope', function($query) use ($lastRan) {
                return $query->where('files.deleted_at', '>=', date("Y-m-d H:i:s", $lastRan))
                             ->orWhere('files.updated_at', '>=', date("Y-m-d H:i:s", $lastRan));
            })
            ->whereNotIn('users.id', array_keys($usersToUpdate))
            ->groupBy('users.id')
            ->get();

        foreach ($fileUsers as $fileUser) {
            $usersToUpdate[$fileUser->id] = $fileUser;
        }

        // We now have all the users to update
        foreach ($usersToUpdate as $user) {
            // Grab the sum of the total storage used for this users projects.
            $projectsTotalStorageUsed = $user->projects()->sum('total_storage_used');
            $filesTotalStorageUsed = $user->filesNoScope()
                ->whereNull('deleted_at')
                ->sum('size');

            // Then update the user with that value.
            $user->total_storage_used = $projectsTotalStorageUsed + $filesTotalStorageUsed;
            $saved = $user->save();

            // If we saved we may want to do some things
            if ($saved) {
                // Broadcast a GraphQL subscription for clients.
                Subscription::broadcast('userStorageUpdated', $user);


                // if (!$user->hasStorageSpaceAvailable()) {
                //     // If the user no longer has enough storage left, then we need to email them.
                //     SendUserStorageLimitReachedEmail::dispatch($user);
                // }
            }
        }
    }
}
