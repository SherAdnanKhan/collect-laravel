<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Storage;

class ZipCreated extends Mailable
{
    /**
     * The fileName of the zip
     *
     * @var string
     */
    protected $fileName;


    /**
     * Create a new message instance.
     *
     * @param string $fileName
     * @return void
     */
    public function __construct($fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $url = Storage::disk('s3')->temporaryUrl(
            $this->fileName, now()->addHours(24)
        );

        return $this->view('emails.users.zip-created')
            ->with([
                'zipUrl' => $url
            ]);
    }
}
