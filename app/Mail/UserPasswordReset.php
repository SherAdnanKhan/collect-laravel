<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Mail\Mailable;

class UserPasswordReset extends Mailable
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
        $resetUrl = config('app.frontend_url') . '/reset-password/' . $this->token . '?email=' . $this->user->email;

        return $this->view('emails.users.password-reset')
            ->with(['resetUrl' => $resetUrl]);
    }
}
