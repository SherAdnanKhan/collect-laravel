<?php

namespace App\Mail;

use App\Models\Share;
use Carbon\Carbon;
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
        $subject = 'Summary of files sent on ' . Carbon::parse($this->share->created_at)->format('Y-m-d');

        return $this->view('emails.users.share-summary')
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject($subject)
            ->with([
                'share' => $this->share,
                'url' => $url
            ]);
    }
}
