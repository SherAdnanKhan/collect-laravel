<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Carbon\Carbon;
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
        $date = Carbon::yesterday()->format('Y-m-d');
        $expiredDownloads = DownloadJob::whereBetween('expires_at', [
                                            $date." 00:00:00",
                                            $date." 23:59:59"
                                        ])
                                        ->pluck('path');

        foreach($expiredDownloads as $expiredDownload) {
            Storage::disk('s3')->delete($expiredDownload);
        }
    }
}
