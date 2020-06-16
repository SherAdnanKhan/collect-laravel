<?php

namespace App\Jobs\Emails;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\NewSubscriptionIndividualPlan;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendNewSubscriptionIndividualPlanEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The user who is now on a individual plan.
     *
     * @var User
     */
    protected $user;


    /**
     * Create a new job instance.
     *
     * @param User $user
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $subscriptionAmount = $this->getSubscriptionAmount();

        Mail::to($this->user->email)->send(
            new NewSubscriptionIndividualPlan($this->user, $subscriptionAmount)
        );
    }

    public function getSubscriptionAmount()
    {
        $subscription = $this->user->subscription(User::SUBSCRIPTION_NAME);

        return $subscription->getLatestInvoiceAmountFormatted();
    }
}
