<?php

namespace App\Jobs;

use App\Mail\CollaboratorInvite;
use App\Models\CollaboratorInvite;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendCollaboratorInviteEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The invite.
     *
     * @var CollaboratorInvite
     */
    protected $invite;

    /**
     * Create a new job instance.
     *
     * @param CollaboratorInvite $invite
     * @return void
     */
    public function __construct(CollaboratorInvite $invite)
    {
        $this->invite = $invite;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->invite->email)->send(new CollaboratorInvite($this->invite));
    }
}
