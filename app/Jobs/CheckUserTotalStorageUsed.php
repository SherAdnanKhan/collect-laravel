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
class CheckUserTotalStorageUsed implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $cacheKey = 'CheckUserTotalStorageUsed-lastran';

        $lastRan = time() - 86400;

        $projects = Project::select('projects.id', 'projects.user_id')
            ->whereHas('allFilesNoScope', function($query) use ($lastRan) {
                return $query->select('files.project_id', 'files.updated_at', 'files.deleted_at')
                    ->where('files.deleted_at', '>=', date("Y-m-d H:i:s", $lastRan))
                    ->orWhere('files.updated_at', '>=', date("Y-m-d H:i:s", $lastRan));
            })
            ->groupBy('projects.id')
            ->with('user:*')
            ->with(['user.subscriptions' => function($query) {
                return $query->select('stripe_plan');
            }])
            ->get();

        $users = [];

        foreach ($projects as $project) {
            if (array_key_exists($project->user->id, $users)) {
                continue;
            }

            $users[$project->user->id] = $project->user;
        }

        $fileUsers = User::select('users.*')
            ->whereHas('filesNoScope', function($query) use ($lastRan) {
                return $query->where('files.deleted_at', '>=', date("Y-m-d H:i:s", $lastRan))
                             ->orWhere('files.updated_at', '>=', date("Y-m-d H:i:s", $lastRan));
            })
            ->whereNotIn('users.id', array_keys($users))
            ->groupBy('users.id')
            ->get();

        foreach ($fileUsers as $fileUser) {
            $users[$fileUser->id] = $fileUser;
        }

        foreach ($users as $user) {
            if ($user->isCloseToStorageLimit()) {
                // If the user no longer has enough storage left, then we need to email them.
                SendUserStorageLimitReachedEmail::dispatch($user);
            }
        }
    }
}
