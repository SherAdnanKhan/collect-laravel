<?php

namespace App\Mail;

use App\Models\CollaboratorInvite as CollaboratorInviteModel;
use App\Models\CollaboratorPermission;
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

        $artistName = '';
        if ($this->invite->project->artist) {
            $artistName = "{$this->invite->project->artist->first_name} {$this->invite->project->artist->last_name}";
        }

        $recordingNames = null;
        if ($this->invite->collaborator->type === 'recording') {
            $recordingNames = join(', ', $this->invite->collaborator->recordings->pluck('name')->toArray());
        }

        $permissions = $this->invite->collaborator->permissions->toArray();

        $fullAccess = false;
        if (!$recordingNames) {
            $permissionTypes = [];
            foreach ($permissions as $permission) {
                if (!isset($permissionTypes[$permission['type']])) {
                    $permissionTypes[$permission['type']] = false;
                }

                if ($permission['level'] != 'create') {
                    continue;
                }

                $permissionTypes[$permission['type']] = true;
            }

            $fullAccess = true;
            foreach ($permissionTypes as $type => $create) {
                if ($create === true) {
                    continue;
                }

                $fullAccess = false;
                break;
            }
        }

        return $this->view('emails.collaborators.invite')
            ->from('noreply@vevacollect.com', 'VEVA Collect')
            ->subject('VEVA Collect invitation from ' . $this->invite->user->name)
            ->with([
                'type'          => $this->invite->collaborator->type,
                'name'          => $this->invite->collaborator->name,
                'permissions'   => array_map(function($item) {
                    return implode(', ', $item);
                }, array_reduce($permissions, function ($carry, $item) {
                    $name = CollaboratorPermission::TYPES_WITH_LABELS[$item['type']];
                    $level = CollaboratorPermission::LEVELS_WITH_LABELS[$item['level']];

                    $carry[$name][] = $level;
                    return $carry;
                }, [])),
                'fullAccess'        => $fullAccess,
                'projectName'       => $this->invite->project->name,
                'projectArtistName' => $artistName,
                'recordingNames'    => $recordingNames,
                'senderName'        => sprintf('%s (%s)', $this->invite->user->name, $this->invite->user->email),
                'inviteUrl'         => $inviteUrl
            ]);
    }
}
