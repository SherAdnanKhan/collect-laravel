<?php

namespace App\Models;

use App\Contracts\UserAccessible;
use App\Models\Collaborators;
use App\Models\Credit;
use App\Models\PartyAddress;
use App\Models\PartyContact;
use App\Models\User;
use App\Traits\UserAccesses;
use App\Util\BuilderQueries\ProjectAccess;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Party extends Model implements UserAccessible
{
    use UserAccesses;
    use SoftDeletes;

    protected $table = 'parties';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'title', 'prefix', 'first_name', 'last_name', 'middle_name',
        'suffix', 'isni', 'type', 'comments',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'death_date' => 'date'
    ];

    /**
     * Allow access to a 'name' attribute for display purposes.
     *
     * @return string
     */
    public function getNameAttribute(): string
    {
        return trim($this->attributes['first_name'] . ' ' . ($this->attributes['middle_name'] ? $this->attributes['middle_name'] . ' ' : '') . $this->attributes['last_name']);
    }

    /**
     * The user owner for this project.
     *
     * @return User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the times this party has been favourited.
     *
     * @return MorphMany
     */
    public function favourites(): MorphMany
    {
        return $this->morphMany(UserFavourite::class, 'favoured');
    }

    /**
     * Get all of a parties credits.
     *
     * @return HasMany
     */
    public function credits(): HasMany
    {
        return $this->hasMany(Credit::class);
    }

    /**
     * Get the parties contact details.
     *
     * @return HasMany
     */
    public function contacts(): HasMany
    {
        return $this->hasMany(PartyContact::class);
    }

    /**
     * Get the parties addresses.
     *
     * @return HasMany
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(PartyAddress::class);
    }

    /**
     * A party is viewable to everyone.
     *
     * @param  Builder $query
     * @param  array   $data
     * @return Builder
     */
    public function scopeUserViewable(Builder $query, $data = []): Builder
    {
        // i own it, it’s public, it’s already related to the project, owned by the owner or a collaborator on the project
        $user = auth()->user();

        return $query->where(function($q) use ($user) {
            return $q->whereHas('credits', function($q) use ($user) {
                return (new ProjectAccess($q, $user, ['project'], ['read'], 'projects'))->getQuery();
            });
        })->orWhere('user_id', $user->getKey())->orWhereNotNull('isni');
    }

    /**
     * A user can update a party if they own it.
     *
     * @param  Builder $query
     * @param  array   $data
     * @return Builder
     */
    public function scopeUserUpdatable(Builder $query, $data = []): Builder
    {
        $user = auth()->user();
        return $query->where('user_id', $user->getKey());
    }

    /**
     * A user can delete a party if they own it.
     *
     * @param  Builder $query
     * @param  array   $data
     * @return Builder
     */
    public function scopeUserDeletable(Builder $query, $data = []): Builder
    {
        $user = auth()->user();
        return $query->where('user_id', $user->getKey());
    }
}
