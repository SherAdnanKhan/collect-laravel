<?php

namespace App\Jobs\Emails;

use App\Mail\ZipCreated;
use App\Models\DownloadJob;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendZipCreatedEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The download job for this zip
     * @var DownloadJob
     */
    protected $download_job;

    /**
     * Create a new job instance.
     *
     * @param User $user
     * @param string $fileName
     * @return void
     */
    public function __construct(DownloadJob $download_job)
    {
        $this->download_job = $download_job;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->download_job->user)->send(new ZipCreated($this->download_job));
    }
}
