<?php

namespace App\Jobs\Emails;

use App\Mail\SubscriptionPaymentSuccessful;
use App\Mail\UserPasswordReset;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendSubscriptionPaymentSuccessfulEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The user we're welcoming
     * @var User
     */
    protected $user;

    /**
     * The subscription that is being updated.
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
     * Create a new job instance.
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
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->user)->send(new SubscriptionPaymentSuccessful($this->user, $this->subscription, $this->invoiceAmountPaid, $this->invoiceUrl));
    }
}
