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
            ->from('noreply@vevacollect.com', 'VEVA Collect')
            ->subject('VEVA Collect invitation from ' . $this->invite->user->name)
            ->with([
                'name'          => $this->invite->collaborator->name,
                'permissions'   => array_reduce($this->invite->collaborator->permissions->toArray(), function ($carry, $item) { $carry[$item["type"]][] = $item["level"]; return $carry; }, []),
                'projectName'   => $this->invite->project->name,
                'projectArtistName' => "{$this->invite->project->artist->first_name} {$this->invite->project->artist->last_name}",
                'recordingName' => $this->invite->recording ? $this->invite->recording->name : null,
                'senderName'    => $this->invite->user->name,
                'inviteUrl'     => $inviteUrl
            ]);
    }
}
