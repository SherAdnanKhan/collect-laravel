<?php

namespace App\Models;

use App\Contracts\UserAccessible;
use App\Models\Credit;
use App\Models\Project;
use App\Models\Recording;
use App\Models\SongRecording;
use App\Models\SongType;
use App\Models\User;
use App\Traits\OrderScopes;
use App\Traits\UserAccesses;
use App\Util\BuilderQueries\ProjectAccess;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;

class Song extends Model implements UserAccessible
{
    use UserAccesses;
    use OrderScopes;

    protected $fillable = [
        'user_id', 'song_type_id', 'iswc', 'title', 'subtitle',
        'title_alt', 'subtitle_alt', 'created_on', 'description',
        'lyrics', 'notes'
    ];

    protected $casts = [
        'created_on' => 'date'
    ];

    /**
     * The user who owns this song.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The user who owns this song.
     *
     * @return BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(SongType::class, 'song_type_id');
    }

    /**
     * Get projects for this song through the relationships
     * to the recordings.
     *
     * @return Collection
     */
    public function projects(): Collection
    {
        return $this->recordings()->get()->pluck('project');
    }

    /**
     * Get all occurences of a recording of this song.
     *
     * @return HasMany
     */
    public function recordings(): HasMany
    {
        return $this->HasMany(Recording::class);
    }

    /**
     * Get the songs favourite row
     *
     * @return MorphMany
     */
    public function favourites(): MorphMany
    {
        return $this->morphMany(UserFavourite::class, 'favoured');
    }

    /**
     * Grab the credits/contributions directly on this resource.
     *
     * @return MorphMany
     */
    public function credits(): MorphMany
    {
        return $this->morphMany(Credit::class, 'contribution');
    }

    /**
     * A scope to filter songs which are accesible by
     * the currently authed user.
     *
     * @param  Builder $query
     * @param  Model  $model
     * @return Builder
     */
    public function scopeUserViewable(Builder $query, $data = []): Builder
    {
        $user = auth()->user();

        // We check to see if the user is a collaborator or
        // owner on a project that this song has been used on or if
        // this song is owned by this user.
        return $query->whereHas('recordings', function($q) use ($user) {
            // We grab the project for the recording and check permission or
            // ownership access on that.
            return (new ProjectAccess($q, $user, ['recording'], ['read']))->getQuery()
                ->select(['recordings.id', 'recordings.project_id']);
        })->orWhere('user_id', $user->getAuthIdentifier())->orWhereNotNull('iswc');
    }

    /**
     * A scope to filter songs which are updatable by
     * the currently authed user.
     *
     * @param  Builder $query
     * @param  Model  $model
     * @return Builder
     */
    public function scopeUserUpdatable(Builder $query, $data = []): Builder
    {
        $user = auth()->user();

        // User must own a song to update it
        return $query->where('user_id', $user->getAuthIdentifier());
    }

    /**
     * A scope to filter songs which are deletable by
     * the currently authed user.
     *
     * @param  Builder $query
     * @param  Model  $model
     * @return Builder
     */
    public function scopeUserDeletable(Builder $query, $data = []): Builder
    {
        $user = auth()->user();

        // User must own a song to delete it.
        return $query->where('user_id', $user->getAuthIdentifier());
    }
}
