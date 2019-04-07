<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use App\Util\RIN\Importer;
use SimpleXMLElement;
use DOMDocument;
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
        // $user = auth()->user();
        $user = User::find(10);

        $importer = new Importer();
        $importer->setUser($user);

        $xml = new DOMDocument();
        $xml->load(file_get_contents(__DIR__.'/../../../resources/8013289A01_rin.xml'));

        if ($xml->schemaValidate(__DIR__.'/../../../resources/full-recording-information-notification.xsd')) {
            // For now just load in a RIN file.
            $importer->fromXML(simplexml_import_dom($xml));
            $importer->import(true);
            return;
        }

        dd(libxml_get_errors());
    }

    /**
     * Handle a request to generate a RIN export
     *
     * @param  Request $request
     * @return Response
     */
    public function export(Request $request)
    {

    }
}
