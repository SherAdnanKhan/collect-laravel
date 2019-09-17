<?php

namespace App\Observers;

use Ramsey\Uuid\Uuid;
use App\Models\Project;

class ProjectObserver
{
    /**
     * Handle the Project "saving" event.
     *
     * @param  \App\Project  $Project
     * @return void
     */
    public function saving(Project $project)
    {
        // Set the default value of the number to be
        // the ID of the row.

        if (is_null($project->number) || empty($project->number)) {
            $project->number = Uuid::uuid4();
        }
    }
}
