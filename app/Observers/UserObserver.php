<?php

namespace App\Observers;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Log;

class UserObserver
{
    /**
     * Handle the folder "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(User $user)
    {
        $user->profile()->save(new UserProfile());
    }
}
