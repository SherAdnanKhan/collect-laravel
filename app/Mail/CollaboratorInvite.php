<?php

namespace App\Mail;

use App\Models\CollaboratorInvite;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CollaboratorInvite extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The invite instance
     *
     * @var CollaboratorInvite
     */
    protected $invite;


    /**
     * Create a new message instance.
     *
     * @param CollaboratorInvite $invite
     * @return void
     */
    public function __construct(CollaboratorInvite $invite)
    {
        $this->invite = $invite;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $inviteUrl = config('app.frontend_url') . '/accept-invite/' . $this->invite->token;

        return $this->view('emails.collaborators.invite')
            ->with([
                'name'        => $this->invite->name,
                'projectName' => $this->invite->project->name,
                'inviteUrl'   => $inviteUrl
            ]);
    }
}
