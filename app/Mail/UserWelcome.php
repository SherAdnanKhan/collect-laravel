<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Mail\Mailable;

class UserWelcome extends Mailable
{

    /**
     * The user instance.
     *
     * @var User
     */
    protected $user;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.users.welcome')
            ->subject('Welcome to VEVA Collect')
            ->with([
                'name' => $this->user->first_name,
            ]);
    }
}
