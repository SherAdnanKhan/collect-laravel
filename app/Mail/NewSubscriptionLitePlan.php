<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Mail\Mailable;

class NewSubscriptionLitePlan extends Mailable
{

    /**
     * The user instance
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
        $email = $this->view('emails.subscriptions.new-lite-plan')
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('Thank you for subscribing to VEVA Collect!')
            ->with([
                'storageLimit' => User::PLAN_STORAGE_LIMITS_PRETTY['free'],
                'name' => $this->user->first_name
            ]);

        return $email;
    }
}
