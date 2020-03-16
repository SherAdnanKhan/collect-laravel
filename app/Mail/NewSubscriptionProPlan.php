<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Mail\Mailable;

class NewSubscriptionProPlan extends Mailable
{

    /**
     * The user instance
     *
     * @var User
     */
    protected $user;

    /**
     * The amount the user has paid for this invoice.
     *
     * @var int
     */
    protected $subscriptionAmount;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @return void
     */
    public function __construct(User $user, $subscriptionAmount)
    {
        $this->user = $user;
        $this->subscriptionAmount = $subscriptionAmount;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->view('emails.subscriptions.new-pro-plan')
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('Thank you for subscribing to VEVA Collect!')
            ->with([
                'name' => $this->user->first_name,
                'storageLimit' => User::PLAN_STORAGE_LIMITS_PRETTY['pro'],
                'invoiceAmountPaid' => $this->subscriptionAmount,
            ]);

        return $email;
    }
}
