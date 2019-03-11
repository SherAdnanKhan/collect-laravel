<?php

namespace App\Jobs;

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

class UpdateUserTotalStorageUsed implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $cache_key = 'UpdateUserTotalStorageUsed-lastran';

        if (Cache::has($cache_key)) {
            $lastran = Cache::get($cache_key);
            Cache::put($cache_key, time(), 30);
        } else {
            $lastran = 0;
            Cache::add($cache_key, time(), 30);
        }

        $projects = DB::table('projects')
                    ->join('files', 'projects.id', '=', 'files.project_id')
                    ->where('files.deleted_at', '>=', date("Y-m-d H:i:s", $lastran))
                    ->orWhere('files.updated_at', '>=', date("Y-m-d H:i:s", $lastran))
                    ->select('projects.id')
                    ->groupBy('projects.id')
                    ->get();

        $users_to_update = [];
        foreach ($projects as $project) {
            $total_storage_used = DB::table('files')
                                  ->where('project_id', $project->id)
                                  ->whereNull('deleted_at')
                                  ->sum('size');

            $project = Project::find($project->id, ['id', 'user_id']);

            $users_to_update[] = $project->user_id;

            Project::where('id', $project->id)
                ->update(['total_storage_used' => $total_storage_used]);
        }

        $users_to_update = array_unique($users_to_update);
        foreach ($users_to_update as $user_id) {
            $total_storage_used = DB::table('projects')
                                  ->where('user_id', $user_id)
                                  // ->whereNull('deleted_at')
                                  ->sum('total_storage_used');

            User::where('id', $user_id)
                ->update(['total_storage_used' => $total_storage_used]);
        }
    }
}
