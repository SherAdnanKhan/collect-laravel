<?php

namespace App\Http\Controllers;

use App\Models\Recording;
use App\Models\Project;
use App\Util\PDFExporter;
use \Illuminate\Http\Request;

/**
 * Responsible for generating and saving PDF exports
 * at either a project or recording level.
 */
class PDFController extends Controller
{
    /**
     * Given either a project or recording, generate
     * and save a PDF of the data as an alternative to
     * a RIN format.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        $user = auth()->user();
        $recording = null;

        if ($request->has('recording')) {
            $recordingId = $request->get('recording');

            $recording = Recording::where('id', $recordingId)->userViewable(['user' => $user])->first();

            if (!$recording) {
                abort(404, 'Unable to find the recording to export as PDF.');
                return;
            }

            $project = $recording->project;
        } else {
            $projectId = $request->get('project');

            $project = Project::where('id', $projectId)->userViewable(['user' => $user])->first();

            if (!$project) {
                abort(404, 'Unable to find the project to export as PDF.');
                return;
            }
        }

        $generator = new PDFExporter($project, $recording);

        $pdf = $generator->generate();

        $filename = sprintf('%s_Credits.pdf', time());

        if ($project->artist) {
            $filename = sprintf("%s_%s_Credits.pdf", $project->artist->first_name, $project->artist->last_name);
        }

        return $pdf->download($filename);
    }
}
