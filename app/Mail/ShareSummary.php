<?php

namespace App\Mail;

use App\Models\Share;
use Illuminate\Mail\Mailable;

class ShareSummary extends Mailable
{
    protected $share;

    /**
     * Create a new message instance.
     *
     * @param Share $share
     * @return void
     */
    public function __construct(Share $share)
    {
        $this->share = $share;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $url = config('app.frontend_url') . '/download/' . $this->share->id . '/u/' . $this->share->user->encrypted_email;

        return $this->view('emails.users.share-summary')
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('VEVA Collect Share Summary')
            ->with([
                'share' => $this->share,
                'url' => $url
            ]);
    }
}
