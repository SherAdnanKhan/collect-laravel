<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Mail\Mailable;

class UserVerification extends Mailable
{

    /**
     * The user instance.
     *
     * @var User
     */
    protected $user;

    /**
     * The token
     *
     * @var string
     */
    protected $token;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param string $token
     * @return void
     */
    public function __construct(User $user, string $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $verifyUrl = config('app.frontend_url') . '/verify/' . $this->token;

        return $this->view('emails.users.verification')
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('Verify your email')
            ->with([
                'name' => $this->user->first_name,
                'verifyUrl' => $verifyUrl
            ]);
    }
}
