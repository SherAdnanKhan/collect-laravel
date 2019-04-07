<?php

namespace App\Observers;

use App\Models\Project;

class ProjectObserver
{
    /**
     * Handle the Project "created" event.
     *
     * @param  \App\Project  $Project
     * @return void
     */
    public function created(Project $project)
    {
        // Set the default value of the number to be
        // the ID of the row.

        if (is_null($project->number)) {
            $project->number = $project->id;
            $project->save();
        }
    }
}
