<?php

namespace App\Traits;

use App\Util\BuilderQueries\ProjectAccess;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User;

/**
 * A trait which provides default implementations for the
 * UserAccessible contract.
 */
trait UserAccesses
{
    /**
     * Should return the type which is used to determine which collaborator
     * resource type we should check for permissions against. By default this is
     * the lowercase name of the class.
     *
     * @return string
     */
    public function getTypeName(): string
    {
        return strtolower((new \ReflectionClass($this))->getShortName());
    }

    /**
     * Provide a default user viewable scope which will by default
     * filter out models where the user doesn't have read permissions on it's
     * related project using the type of the resource.
     *
     * @param  Builder $query
     * @param  array   $data
     * @return Builder
     */
    public function scopeUserViewable(Builder $query, $data = []): Builder
    {
        $user = auth()->user();

        return $this->wrapUserRelationCheck(
            $user,
            (new ProjectAccess($query, $user, [$this->getTypeName()], ['read']))->getQuery()
        );
    }

    /**
     * Provide a default user updatab;e scope which will by default
     * filter out models where the user doesn't update read permissions on it's
     * related project using the type of the resource.
     *
     * @param  Builder $query
     * @param  array   $data
     * @return Builder
     */
    public function scopeUserUpdatable(Builder $query, $data = []): Builder
    {
        $user = auth()->user();

        return $this->wrapUserRelationCheck(
            $user,
            (new ProjectAccess($query, auth()->user(), [$this->getTypeName()], ['update']))->getQuery()
        );
    }

    /**
     * Provide a default user deletable scope which will by default
     * filter out models where the user doesn't have delete permissions on it's
     * related project using the type of the resource.
     *
     * @param  Builder $query
     * @param  array   $data
     * @return Builder
     */
    public function scopeUserDeletable(Builder $query, $data = []): Builder
    {
        $user = auth()->user();

        return $this->wrapUserRelationCheck(
            $user,
            (new ProjectAccess($query, auth()->user(), [$this->getTypeName()], ['delete']))->getQuery()
        );
    }

    private function wrapUserRelationCheck(User $user, Builder $query): Builder
    {
        if (method_exists($this, 'user')) {
            $key = array_get($this->user()->meta(), 'belongsToId');
            $query = $query->orWhere($key, $user->getAuthIdentifier());
        }

        return $query;
    }
}
