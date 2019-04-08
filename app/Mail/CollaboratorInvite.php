<?php

namespace App\Mail;

use App\Models\CollaboratorInvite as CollaboratorInviteModel;
use Illuminate\Mail\Mailable;

class CollaboratorInvite extends Mailable
{
    /**
     * The invite instance
     *
     * @var CollaboratorInviteModel
     */
    protected $invite;


    /**
     * Create a new message instance.
     *
     * @param CollaboratorInviteModel $invite
     * @return void
     */
    public function __construct(CollaboratorInviteModel $invite)
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
        $inviteUrl = config('app.frontend_url') . '/invite/' . $this->invite->token;

        return $this->view('emails.collaborators.invite')
            ->subject('VEVA Collect invitation from ' . $this->invite->user->name)
            ->with([
                'name'        => $this->invite->collaborator->name,
                'projectName' => $this->invite->project->name,
                'senderName'  => $this->invite->user->name,
                'inviteUrl'   => $inviteUrl
            ]);
    }
}
