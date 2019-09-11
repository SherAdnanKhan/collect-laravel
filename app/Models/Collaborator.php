<?php

namespace App\Models;

use App\Contracts\EventLoggable;
use App\Contracts\UserAccessible;
use App\Models\CollaboratorInvite;
use App\Models\CollaboratorPermission;
use App\Models\Project;
use App\Models\Recording;
use App\Models\User;
use App\Traits\EventLogged;
use App\Traits\UserAccesses;
use App\Util\BuilderQueries\ProjectAccess;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Collaborator extends Model implements UserAccessible, EventLoggable
{
    use UserAccesses;
    use EventLogged;

    const TYPE_NORMAL = 'normal';
    const TYPE_RECORDING = 'recording';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'user_id', 'project_id', 'email', 'name'
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
     * The recordings this collaborator has access to.
     *
     * @return BelongsToMany
     */
    public function recordings(): BelongsToMany
    {
        return $this->belongsToMany(Recording::class, 'collaborators_to_recordings');
    }

    /**
     * Get the collaborators permissions.
     *
     * @return HasMany
     */
    public function permissions(): HasMany
    {
        return $this->hasMany(CollaboratorPermission::class);
    }

    /**
     * Get the invite for this collaborator
     *
     * @return HasOne
     */
    public function invite(): HasOne
    {
        return $this->hasOne(CollaboratorInvite::class);
    }

    /**
     * The identifier for event log will be the users name.
     *
     * @return string
     */
    public function getIdentifier(): string
    {
        if ($this->user) {
            return $this->user->name;
        }

        return $this->name;
    }

    /**
     * Create a new invite and send it.
     *
     * @return CollaboratorInvite
     */
    public function createAndSendInvite(): CollaboratorInvite
    {
        $user = auth()->user();

        $invite = new CollaboratorInvite([
            'token'        => str_random(60),
            'project_id'   => $this->project_id,
            'recording_id' => $this->recording_id,
            'user_id'      => ($user ? $user->id : null)
        ]);

        $saved = $this->invite()->save($invite);

        if ($saved) {
            $invite->sendNotification();
        }

        return $invite;
    }
}
