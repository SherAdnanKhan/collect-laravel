<?php

namespace App\Observers;

use App\Models\Credit;
use App\Models\Project;
use App\Models\Recording;
use App\Models\Song;
use Illuminate\Support\Facades\Log;

class CreditObserver
{
    /**
     * Handle the folder "saved" event.
     *
     * @param  \App\Models\Credit  $credit
     * @return void
     */
    public function saved(Credit $credit)
    {
        $credit->projects()->detach();
        $contribution = $credit->contribution;

        $projects = [];

        if ($contribution instanceof Song) {
            $projects = $contribution->projects()->pluck('id');
        } else if ($contribution instanceof Project) {
            $projects[] = $contribution->id;
        } else {
            if (method_exists($contribution, 'project')) {
                $projects[] = $contribution->project->id;
            }
        }

        $credit->projects()->attach($projects);

        switch($credit->contribution_type) {
            case "project":
                $credit->projects->searchable();
            break;
            case "song":
                Song::find($credit->contribution_id)->searchable();
            break;
            case "recording":
                Recording::find($credit->contribution_id)->searchable();
            break;
        }
    }

    public function deleted(Credit $credit)
    {
        $credit->projects->searchable();

        if($credit->contribution_type == "song") {
            Song::find($credit->contribution_id)->searchable();
        }
    }
}
