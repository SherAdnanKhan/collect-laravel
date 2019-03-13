<?php

namespace App\Models;

use App\Contracts\UserAccessible;
use App\Models\Collaborators;
use App\Models\Credit;
use App\Models\PartyContact;
use App\Models\PartyAddress;
use App\Models\User;
use App\Traits\UserAccesses;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Party extends Model implements UserAccessible
{
    use UserAccesses;

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

    protected $dates = ['birth_date', 'death_date'];

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
        return $query;
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
        return $query->where('user_id', $user->id);
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
        return $query->where('user_id', $user->id);
    }
}
