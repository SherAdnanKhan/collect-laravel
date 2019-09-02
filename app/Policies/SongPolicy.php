<?php

namespace App\Policies;

use App\Models\User;
use App\Song;
use Illuminate\Auth\Access\HandlesAuthorization;

class SongPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can delete the song.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Song  $song
     * @return mixed
     */
    public function delete(User $user, Song $song)
    {
        return $song->recordings->count() === 0;
    }
}
