<?php

namespace App\Mail;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Mail\Mailable;

class SubscriptionPaymentFailed extends Mailable
{
    /**
     * The user instance
     *
     * @var User
     */
    protected $user;

    /**
     * The users subscription which was updated.
     *
     * @var Subscription
     */
    protected $subscription;


    /**
     * Create a new message instance.
     *
     * @param User $user
     * @return void
     */
    public function __construct(User $user, Subscription $subscription)
    {
        $this->user = $user;
        $this->subscription = $subscription;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.subscriptions.payment-failed')
            ->with([
                'name' => $this->user->name,
            ]);
    }
}
