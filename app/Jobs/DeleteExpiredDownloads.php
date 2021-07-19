<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\DownloadJob;
use Illuminate\Support\Facades\Storage;

class DeleteExpiredDownloads implements ShouldQueue
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
        $currentTime = Carbon::now();
        $expiredDownloads = DownloadJob::where('expires_at', '<', $currentTime)
                                ->where('expires_at','>',$currentTime->subDay('1'))
                                ->pluck('path');
        foreach($expiredDownloads as $expiredDownload) {
            if(Storage::disk('s3')->exists($expiredDownload)) {
                Storage::disk('s3')->delete($expiredDownload);
            }
        }
    }
}
