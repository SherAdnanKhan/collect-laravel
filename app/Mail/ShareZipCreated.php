<?php

namespace App\Mail;

use App\Models\Share;
use App\Models\ShareUser;
use Carbon\Carbon;
use Illuminate\Mail\Mailable;

class ShareZipCreated extends Mailable
{
    protected $user;
    /**
     * The share this zip
     * @var Share
     */
    protected $share;

    /**
     * Create a new message instance.
     *
     * @param ShareUser $user
     * @param Share $share
     * @return void
     */
    public function __construct(ShareUser $user, Share $share)
    {
        $this->user = $user;
        $this->share = $share;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $url = config('app.frontend_url') . '/download/' . $this->share->id . '/u/' . $this->user->encrypted_email;

        return $this->view('emails.users.share-zip-created')
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('VEVA Collect Share Link Received!')
            ->with([
                'zipUrl' => $url,
                'expiry' => Carbon::parse($this->share->expires_at)->toDayDateTimeString()
            ]);
    }
}
