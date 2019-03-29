<?php

namespace App\Jobs;

use App\Models\EventLog;
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
 * Works out who's cache keys to increment for unread event log
 * / activity items.
 */
class IncrementEventLogUnreadCounter implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $eventLog;

    public function __construct(EventLog $eventLog)
    {
        $this->eventLog = $eventLog;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $project = $this->eventLog->project;

        // Get ids of all users who will see this,
        // excluding the id of the user who created this.
        $users = User::select('users.id', 'users.created_at')
            ->where('id', '<>', $this->eventLog->user_id)
            ->whereHas('projects', function($query) use ($project) {
                return $query->select('projects.id')->where('id', $project->id);
            })
            ->orWhereHas('collaborators', function($query) use ($project) {
                return $query->select('collaborators.id', 'collaborators.project_id', 'collaborators.user_id')
                    ->where('project_id', $project->id)
                    ->whereHas('permissions', function($query) {
                        return $query->select('level', 'type')
                            ->where('level', 'read')
                            ->where('type', 'project');
                    });
            })->get();

        // Let's go over all users who can see this
        // and increment their counters.
        foreach ($users as $user) {
            // Increment the counter for the user in context
            // of the project which this event happened.
            $counterKey = sprintf(EventLog::UNREAD_CACHE_KEY_FORMAT, $project->id, $user->id);
            Cache::increment($counterKey);

            // If the user doesn't have a last read in this context, we'll
            // set it to the timestamp they were created.
            $lastReadKey = sprintf(EventLog::LAST_READ_CACHE_KEY_FORMAT, $project->id, $user->id);
            if (!Cache::has($lastReadKey)) {
                Cache::set($lastReadKey, $user->created_at);
            }

            Subscription::broadcast('userUnreadActivities', [
                'project_id' => $project->id,
                'user_id'    => $user->id,
                'count'      => Cache::get($counterKey, 0),
            ]);
        }
    }
}
