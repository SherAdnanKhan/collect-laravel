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
        $inviteUrl = config('app.frontend_url') . '/accept-invite/' . $this->invite->token;

        $name = $this->invite->collaborator->name;
        $email = $this->invite->collaborator->email;

        return $this->view('emails.collaborators.invite')
            ->with([
                'name'        => $name,
                'projectName' => $email,
                'inviteUrl'   => $inviteUrl
            ]);
    }
}
