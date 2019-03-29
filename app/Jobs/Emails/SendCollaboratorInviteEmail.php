<?php

namespace App\Jobs\Emails;

use App\Mail\CollaboratorInvite;
use App\Models\CollaboratorInvite as CollaboratorInviteModel;
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
     * @var CollaboratorInviteModel
     */
    protected $invite;

    /**
     * Create a new job instance.
     *
     * @param CollaboratorInviteModel $invite
     * @return void
     */
    public function __construct(CollaboratorInviteModel $invite)
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
        Mail::to($this->invite->collaborator->email)->send(new CollaboratorInvite($this->invite));
    }
}
