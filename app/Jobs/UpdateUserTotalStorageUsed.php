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
        $cache_key = 'UpdateUserTotalStorageUsed-lastran';

        // Grab last ran, default 0 if it doesn't exist
        $last_ran = Cache::get($cache_key, 0);

        // Then put the current time in the cache for 30 minutes.
        Cache::put($cache_key, time(), 30);

        // Grab projects which have files that have been updated or deleted
        // since we last ran this job. And eager load all the users, so we can
        // grab them all in a single query.
        $projects = Project::select('projects.id', 'projects.user_id')
            ->whereHas('allFilesNoScope', function($query) use ($last_ran) {
                return $query->select('files.project_id', 'files.updated_at', 'files.deleted_at')
                    ->where('files.deleted_at', '>=', date("Y-m-d H:i:s", $last_ran))
                    ->orWhere('files.updated_at', '>=', date("Y-m-d H:i:s", $last_ran));
            })
            ->groupBy('projects.id')
            ->with('user:id,first_name,last_name')
            ->with(['user.subscriptions' => function($query) {
                return $query->select('stripe_plan');
            }])
            ->get();

        // Array to keep track of which users nee to have
        // the storage recalculated.
        $users_to_update = [];

        // We go over each project and grab a sum of the non-deleted files sizes.
        foreach ($projects as $project) {
            $total_storage_used = $project->allFiles()
                ->whereNull('deleted_at')
                ->sum('size');

            // Run an update to save that value.
            $saved = $project->save(['total_storage_used' => $total_storage_used]);

            // If it's saved and we already haven't marked a user to update, do so.
            if ($saved && !array_key_exists($project->user->id, $users_to_update)) {
                $users_to_update[$project->user->id] = $project->user;
            }
        }

        // We now have all the users to update
        foreach ($users_to_update as $user) {
            // Grab the sum of the total storage used for this users projects.
            $total_storage_used = $user->projects()->sum('total_storage_used');

            // Then update the user with that value.
            $saved = $user->save(['total_storage_used' => $total_storage_used]);

            // If we saved we may want to do some things
            if ($saved) {
                // Broadcast a GraphQL subscription for clients.
                Subscription::broadcast('userStorageUpdated', $user);


                if (!$user->hasStorageSpaceAvailable()) {
                    // If the user no longer has enough storage left, then we need to email them.
                    SendUserStorageLimitReachedEmail::dispatch($user);
                }
            }
        }
    }
}
