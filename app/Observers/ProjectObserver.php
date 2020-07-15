<?php

namespace App\Observers;

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

        if ($project->hasAttribute('number') && empty($project->number)) {
            $number = '';
            for($i = 0; $i < 14; $i++) {
                $number .= mt_rand(0, 9);
            }

            $project->number = 'VEVA' . $number;
        }
    }
}
