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
        $subject = $this->share->user->name . ' has sent you files via VEVA Collect';
        $data = [
            'zipUrl' => $url,
            'mailMessage' => $this->share->message,
            'expiry' => null
        ];

        if (isset($this->share->expires_at)) {
            $data['expiry'] = Carbon::parse($this->share->expires_at)->format('M d, Y');
        }

        return $this->view('emails.users.share-zip-created')
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->replyTo($this->share->user->email, $this->share->user->name)
            ->subject($subject)
            ->with($data);
    }
}
