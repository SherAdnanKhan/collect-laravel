<?php

namespace App\Jobs\Emails;

use App\Mail\NewSubscriptionProPlan;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendNewSubscriptionProPlanEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The user who is now on a pro plan.
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
            new NewSubscriptionProPlan($this->user, $subscriptionAmount)
        );
    }

    private function getSubscriptionAmount()
    {
        $subscription = $this->user->subscription(User::SUBSCRIPTION_NAME);

        return $subscription->getPlanCostFormatted();
    }
}
