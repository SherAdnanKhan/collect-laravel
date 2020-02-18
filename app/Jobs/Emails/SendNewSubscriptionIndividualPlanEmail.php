<?php

namespace App\Jobs\Emails;

use App\Mail\NewSubscriptionIndividualPlan;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

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
     * The amount that would be paid.
     *
     * @var int
     */
    protected $subscriptionAmount;

    /**
     * Create a new job instance.
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
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->user->email)->send(
            new NewSubscriptionIndividualPlan($this->user, $this->subscriptionAmount)
        );
    }
}
