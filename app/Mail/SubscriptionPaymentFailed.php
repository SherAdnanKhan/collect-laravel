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
        $accountUrl = config('app.frontend_url') . '/subscription';

        return $this->view('emails.subscriptions.payment-failed')
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('VEVA Collect Payment Issue')
            ->with([
                'name'       => $this->user->first_name,
                'accountUrl' => $accountUrl
            ]);
    }
}
