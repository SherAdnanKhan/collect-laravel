<?php

namespace App\Jobs\Emails;

use App\Mail\ShareZipCreated;
use App\Models\Share;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendShareZipCreatedEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The share for this zip
     * @var Share
     */
    protected $share;

    /**
     * Create a new job instance.
     *
     * @param Share $share
     * @return void
     */
    public function __construct(Share $share)
    {
        $this->share = $share;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->share->users as $user) {
            Mail::to($user)->send(new ShareZipCreated($user, $this->share));
        }
    }
}
