<?php

namespace App\Mail;

use App\Models\DownloadJob;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Storage;

class ZipCreated extends Mailable
{
    /**
     * The download job for this zip
     * @var DownloadJob
     */
    protected $download_job;

    /**
     * Create a new message instance.
     *
     * @param DownloadJob $download_job
     * @return void
     */
    public function __construct(DownloadJob $download_job)
    {
        $this->download_job = $download_job;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $url = config('app.frontend_url') . '/share/' . $this->download_job->id;

        return $this->view('emails.users.zip-created')
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('Your VEVA Collect Download is Ready!')
            ->with([
                'zipUrl' => $url,
                'name'   => $this->download_job->user->first_name,
            ]);
    }
}
