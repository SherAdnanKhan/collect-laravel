<?php

namespace App\Jobs;

use App\Models\File;
use App\Models\Folder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Calculates the users total storage used in bytes.
 */
class DeleteFiles implements ShouldQueue
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
        $filesToDelete = [];

        $deletedFiles = File::onlyTrashed()->get();
        foreach ($deletedFiles as $file) {
            Log::info('Deleting File '.$file->path);

            $hasNewerVersion = (bool)File::where('path', $file->path)->count();
            if ($hasNewerVersion) {
                $file->forceDelete();
                continue;
            }

            $filesToDelete[] = $file->path;
            $file->forceDelete();
        }

        $deletedFolders = Folder::onlyTrashed()->get();
        foreach ($deletedFolders as $folder) {
            $folder->forceDelete();
        }

        if (!empty($filesToDelete)) {
            $s3 = Storage::disk('s3')->getDriver()->getAdapter()->getClient();
            try {
                $s3->deleteObjects([
                    'Bucket' => config('filesystems.disks.s3.bucket'),
                    'Delete' => [
                        'Objects' => array_map(function ($file) {
                            return ['Key' => $file];
                        }, $filesToDelete)
                    ],
                ]);
            } catch (\Exception $e) {
                report($e);
            }
        }
    }
}
