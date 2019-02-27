<?php

namespace App\Models;

use App\Models\Collaborators;
use App\Models\PersonSession;
use App\Models\Session;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Person extends Model
{
    protected $table = 'persons';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'name', 'email',
    ];

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
     * Get all sessions this person is associated to.
     *
     * @return BelongsToMany
     */
    public function sessions(): BelongsToMany
    {
        return $this->belongsToMany(Session::class, 'persons_to_sessions')
            ->using(PersonSession::class);
    }

    /**
     * Get the times this person has been favourited.
     *
     * @return MorphMany
     */
    public function favourites(): MorphMany
    {
        return $this->morphMany(UserFavourite::class, 'favoured');
    }

    /**
     * A scope to filter visible people by user ownership.
     *
     * @param  Builder $query
     * @return Builder
     */
    public function scopeUserViewable(Builder $query): Builder
    {
        $user = auth()->user();

        return $query->whereHas('user', function($query) {
            return (new ProjectAccess($query, $user, ['read'], 'projects'))->getQuery();
        })->orWhere('user_id', $user->getAuthIdentifier());
    }
}
