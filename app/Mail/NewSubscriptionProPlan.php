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
    protected $invoiceAmountPaid;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @return void
     */
    public function __construct(User $user, $invoiceAmountPaid)
    {
        $this->user = $user;
        $this->invoiceAmountPaid = $invoiceAmountPaid;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->view('emails.subscriptions.new-pro-plan')
            ->subject('Thank you for subscribing to VEVA Collect!')
            ->with([
                'name' => $this->user->first_name,
                'invoiceAmountPaid' => $this->invoiceAmountPaid,
            ]);

        return $email;
    }
}
