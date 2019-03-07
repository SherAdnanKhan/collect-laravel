<?php

namespace App\Models;

use App\Models\CollaboratorPermission;
use App\Models\Project;
use App\Models\User;
use App\Util\BuilderQueries\ProjectAccess;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Collaborator extends Model
{
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
     * Determine when a user can see the collaborators.
     *
     * @param  Builder $builder
     * @param  array   $data
     * @return Builder
     */
    public function scopeUserViewable(Builder $builder, $data = []): Builder
    {
        $user = auth()->user();

        return (new ProjectAccess($q, $user, ['collaborator'], ['read']))->getQuery()
            ->orWhere('user_id', $user->getAuthIdentifier());
    }
}
