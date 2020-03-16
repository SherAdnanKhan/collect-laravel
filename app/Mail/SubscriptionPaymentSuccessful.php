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
     * The url to the invoice for this payment
     * @var string
     */
    protected $invoiceUrl;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @return void
     */
    public function __construct(User $user, Subscription $subscription, $invoiceAmountPaid, $invoiceUrl)
    {
        $this->user = $user;
        $this->subscription = $subscription;
        $this->invoiceAmountPaid = $invoiceAmountPaid;
        $this->invoiceUrl = $invoiceUrl;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->view('emails.subscriptions.payment-successful')
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('Thank you for your payment!')
            ->with([
                'name'              => $this->user->first_name,
                'invoiceAmountPaid' => $this->invoiceAmountPaid,
                'planName'          => ucfirst($this->subscription->stripe_plan)
            ]);

        // $tmpfname = tempnam('/tmp', 'invoice');
        // if (@file_put_contents($tmpfname, file_get_contents($this->invoiceUrl))) {
        //     $email->attach($tmpfname, [
        //         'as' => 'invoice.pdf',
        //     ]);
        // }

        return $email;
    }
}
