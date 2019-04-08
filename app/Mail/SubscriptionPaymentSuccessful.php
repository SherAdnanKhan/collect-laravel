<?php

namespace App\Mail;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Mail\Mailable;

class SubscriptionPaymentSuccessful extends Mailable
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
     * The total amount of this payment
     * @var string
     */
    protected $invoiceAmountPaid;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @return void
     */
    public function __construct(User $user, Subscription $subscription, $invoiceAmountPaid)
    {
        $this->user = $user;
        $this->subscription = $subscription;
        $this->invoiceAmountPaid = $invoiceAmountPaid;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.subscriptions.payment-successful')
            ->subject('Thank you for your payment!')
            ->with([
                'name'              => $this->user->first_name,
                'invoiceAmountPaid' => $this->invoiceAmountPaid,
                'planName'          => ucfirst($this->subscription->stripe_plan)
            ]);
    }
}
