<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use App\Util\RIN\Exporter;
use App\Util\RIN\Importer;
use DOMDocument;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use SimpleXMLElement;
use \Illuminate\Http\Request;

class RINController extends Controller
{
    /**
     * Handle importing a RIN file into the system.
     *
     * @param  Request $request
     * @return Response
     */
    public function import(Request $request)
    {
        try {
            $this->validate($request, [
                'rin' => 'mimetypes:text/plain,application/xml,text/xml',
            ]);
        } catch (ValidationException $e) {
            abort(400, 'Uploaded file does not have correct Mime type.');
        }

        if (!$request->file('rin')->isValid()) {
            abort(400, 'Uploaded file is not valid.');
        }

        $user = auth()->user();

        $projectId = $request->get('project');

        $project = Project::where('id', $projectId)->userImportable(['user' => $user])->first();
        if (!$project) {
            abort(404, 'Unable to find the project to import to.');
            return;
        }

        try {
            $importer = new Importer();
            $importer->setProject($project);
            $importer->setUser($user);

            $uploadedRIN = $request->file('rin');

            $xml = new DOMDocument();
            $xml->load($uploadedRIN->getPathName());

            if (false && !$xml->schemaValidate(__DIR__.'/../../../resources/full-recording-information-notification.xsd')) {
                abort(400, 'Uploaded file is not valid.');
                return;
            }

            try {
                // Save the imported file in s3.
                $xmlContents = $xml->saveXML();
                $exportPath = 'rin/imports/%s/%s.xml';
                Storage::disk('s3')->put(sprintf($exportPath, $project->getKey(), time()), $xmlContents);
            } catch (\Exception $e) {
                Log::error($e->getMessage());
            }

            // For now just load in a RIN file.
            $importer->fromXML(simplexml_import_dom($xml));

            // Import & override.
            $override = $request->get('override', 0);
            $importer->import((bool) $override);
        } catch (\Exception $e) {
            Log::error($e->getMessage() . ':' . $e->getTraceAsString());
            abort(400, 'Encountered an issue when importing RIN');
        }

        return response()->json([
            'imported' => true,
        ]);
    }

    /**
     * Handle a request to generate a RIN export
     *
     * @param  Request $request
     * @return Response
     */
    public function export(Request $request)
    {
        $user = auth()->user();
        $projectId = $request->get('project');

        $project = Project::where('id', $projectId)->userViewable(['user' => $user])->first();

        if (!$project) {
            abort(404, 'Unable to find the project to export.');
            return;
        }

        $exporter = new Exporter($project, config('app.version', 1));
        $exporter->setUser($user);

        $xmlContents = $exporter->toXML();

        try {
            // Save the exported rin as a file in s3.
            $exportPath = 'rin/exports/%s/%s.xml';
            Storage::disk('s3')->put(sprintf($exportPath, $project->getKey(), time()), $xmlContents);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        ob_start();
        echo $xmlContents;

        header('Content-Type: application/xml');
        die(ob_get_clean());
    }
}
