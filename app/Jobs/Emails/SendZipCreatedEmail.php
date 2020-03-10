<?php

namespace App\Jobs\Emails;

use App\Mail\ZipCreated;
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
     * The user that generated the download
     * @var User
     */
    protected $user;

    /**
     * The filename of the zip that was generated
     * @var string
     */
    protected $fileName = '';

    /**
     * Create a new job instance.
     *
     * @param User $user
     * @param string $fileName
     * @return void
     */
    public function __construct(User $user, $fileName)
    {
        $this->user = $user;
        $this->fileName = $fileName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->user)->send(new ZipCreated($this->user, $this->fileName));
    }
}
