<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Util\RIN\Importer;
use \Illuminate\Http\Request;
use SimpleXMLElement;

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
        $importer = new Importer();

        // For now just load in a RIN file.
        $importer->fromXML(new SimpleXMLElement(file_get_contents(__DIR__.'/../../../resources/8013289A01_rin.xml')));
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
