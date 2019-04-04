<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use App\Util\RIN\Importer;
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
        // $user = auth()->user();
        $user = User::find(10);

        $importer = new Importer();
        $importer->setUser($user);

        // For now just load in a RIN file.
        $importer->fromXML(new SimpleXMLElement(file_get_contents(__DIR__.'/../../../resources/8013289A01_rin.xml')));

        $importer->import(true);
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
