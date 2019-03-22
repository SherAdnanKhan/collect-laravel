<?php

namespace App\Models;

use App\Contracts\EventLoggable;
use App\Contracts\UserAccessible;
use App\Models\CollaboratorInvite;
use App\Models\CollaboratorPermission;
use App\Models\Project;
use App\Models\User;
use App\Traits\EventLogged;
use App\Traits\UserAccesses;
use App\Util\BuilderQueries\ProjectAccess;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Collaborator extends Model implements UserAccessible, EventLoggable
{
    use UserAccesses;
    use EventLogged;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'project_id',
    ];

    /**
     * Get the name of the user as the collaborator.
     *
     * @return string
     */
    public function getNameAttribute(): string
    {
        return $this->user->name;
    }

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
        return $this->user->name;
    }

    /**
     * Create a new invite and send it.
     *
     * @param  string $email
     * @param  string $name
     * @return Boolean
     */
    public function createAndSendInvite($email, $name): bool
    {
        $invite = new CollaboratorInvite([
            'token'      => str_random(60),
            'project_id' => $this->project->id,
            'name'       => $name,
            'email'      => $email,
        ]);

        // If they're inviting an existing user
        // we'll just use the values from that user.
        if ($this->user) {
            $invite->name = $this->user->name;
            $invite->email = $this->user->email;
        }

        $saved = $this->invite()->save($invite);

        if ($saved) {
            $invite->sendNotification();
        }

        return $saved;
    }
}
