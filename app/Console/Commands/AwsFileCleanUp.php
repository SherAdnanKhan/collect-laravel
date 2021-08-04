<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Share;
use App\Models\DownloadJob;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;


class AwsFileCleanUp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'awsfile:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Will Delete Old Files from bucket that are already expired';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $currentTime = Carbon::now();
        $expiredShares = Share::where('status',Share::STATUS_EXPIRED)->pluck('path');
        $expiredDownloads = DownloadJob::where('expires_at', '<', $currentTime)->pluck('path');
        $expiredFilePaths = $expiredShares->merge($expiredDownloads);

        foreach($expiredFilePaths as $expiredFilePath) {
            Storage::disk('s3')->delete($expiredFilePath);
        }

    }
}
