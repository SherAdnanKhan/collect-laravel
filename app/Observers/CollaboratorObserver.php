<?php

namespace App\Observers;

use App\Models\Collaborator;
use App\Models\Project;
use App\Models\Song;
use Illuminate\Support\Facades\Log;

class CollaboratorObserver
{
    /**
     * Handle the folder "created" event.
     *
     * @param  \App\Models\Collaborator  $collaborator
     * @return void
     */
    public function created(Collaborator $collaborator)
    {
        // Setup the default permissions.

        $types = [];
    }
}
