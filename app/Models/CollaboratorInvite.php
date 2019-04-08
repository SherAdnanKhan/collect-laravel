<?php

namespace App\Models;

use App\Jobs\Emails\SendCollaboratorInviteEmail;
use App\Models\Collaborator;
use App\Models\Project;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Notifications\Notifiable;

class CollaboratorInvite extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id', 'collaborator_id', 'token', 'user_id'
    ];

    /**
     * The user who this collaborator represents
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The project this collaborator belongs to.
     *
     * @return BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the collaborator row associated to this invite.
     *
     * @return BelongsTo
     */
    public function collaborator(): BelongsTo
    {
        return $this->belongsTo(Collaborator::class);
    }

    /**
     * Send the notification to the invited collaborator.
     *
     * @return void
     */
    public function sendNotification()
    {
        SendCollaboratorInviteEmail::dispatch($this);
    }
}
