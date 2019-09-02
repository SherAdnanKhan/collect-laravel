<?php

namespace App\Policies;

use App\Models\User;
use App\Party;
use Illuminate\Auth\Access\HandlesAuthorization;

class PartyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can delete the Party.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Party  $party
     * @return mixed
     */
    public function delete(User $user, Party $party)
    {
        return $party->recordings->count() === 0;
    }
}
