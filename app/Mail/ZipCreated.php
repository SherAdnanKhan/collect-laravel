<?php

namespace App\Mail;

use App\Models\User;
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
     * The user who's download has been created.
     *
     * @var User
     */
    protected $user;

    /**
     * Create a new message instance.
     *
     * @param string $fileName
     * @return void
     */
    public function __construct(User $user, $fileName)
    {
        $this->user = $user;
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
            substr($this->fileName, 1), now()->addHours(24)
        );

        return $this->view('emails.users.zip-created')
            ->subject('Your VEVA Collect Download is Ready!')
            ->with([
                'zipUrl' => $url,
                'name'   => $this->user->name,
            ]);
    }
}
