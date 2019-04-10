<?php

namespace App\Models;

use App\Contracts\UserAccessible;
use App\Models\CreditRole;
use App\Models\Instrument;
use App\Models\Party;
use App\Models\Project;
use App\Models\SongRecording;
use App\Traits\UserAccesses;
use App\Util\BuilderQueries\ProjectAccess;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Credit extends Model implements UserAccessible
{
    use UserAccesses;
    use SoftDeletes;

    const TYPES = [
        'project',
        'session',
        'recording',
        'song',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'party_id', 'contribution_id', 'contribution_type', 'credit_role_id', 'performing', 'instrument_id', 'split', 'credit_role_user_defined_value', 'instrument_user_defined_value',
    ];

    /**
     * Get the role for this credit.
     *
     * @return BelongsTo
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(CreditRole::class, 'credit_role_id');
    }

    /**
     * Get the contributed resource.
     *
     * @return MorphTo
     */
    public function contribution(): MorphTo
    {
        return $this->morphTo(null, 'contribution_type', 'contribution_id');
    }

    /**
     * Get the party who is being credited.
     *
     * @return BelongsTo
     */
    public function party(): BelongsTo
    {
        return $this->belongsTo(Party::class);
    }

    /**
     * Get the projects this credit is a part of.
     *
     * @return BelongsToMany
     */
    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'credits_to_projects');
    }

    /**
     * Get the instrument for this credit.
     *
     * @return BelongsTo
     */
    public function instrument(): BelongsTo
    {
        return $this->belongsTo(Instrument::class);
    }

    /**
     * Users can view a credit scope
     *
     * @param  Builder $query
     * @param  array   $data
     * @return Builder
     */
    public function scopeUserViewable(Builder $query, $data = []): Builder
    {
        return $query;
    }

    /**
     * Users can update a credit scope
     *
     * @param  Builder $query
     * @param  array   $data
     * @return Builder
     */
    public function scopeUserUpdatable(Builder $query, $data = []): Builder
    {
        return $query;
    }

    /**
     * Users can delete a credit scope
     *
     * @param  Builder $query
     * @param  array   $data
     * @return Builder
     */
    public function scopeUserDeletable(Builder $query, $data = []): Builder
    {
        // TODO:
        // - A credit is only deletable by someone who as permissions to
        // on the project with which the credit is referencing a resource of,
        // need to relate to a project in order to determine this.
        return $query;
    }
}
