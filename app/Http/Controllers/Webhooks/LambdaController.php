<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Models\File;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\DB;
use \Illuminate\Http\Request;

/**
 * Handle lambda webhook requests.
 */
class LambdaController extends Controller
{
    public function updateFileInfo(Request $request)
    {
        $user = auth()->user();
        if ($user->name !== 'Lambda') {
            throw new AuthenticationException;
        }

        // Only way I could get this to work
        // was to grab the underlying PDO driver
        // and run it directly...
        $pdo = app('db')->getPdo();
        $queries = [];
        $query_data = [];

        foreach ($request->input('Files') as $file_i => $file) {
            $queries[] = 'update files set
                bitrate = '.$pdo->quote($file['BitRate']).',
                bitdepth = '.$pdo->quote($file['BitDepth']).',
                duration = '.$pdo->quote($file['Duration']).',
                size = '.$pdo->quote($file['Size']).',
                samplerate = '.$pdo->quote($file['SampleRate']).',
                numchans = '.$pdo->quote($file['NumChans']).',
                transcoded_path = '.$pdo->quote($file['TranscodedPath']).',
                status = '.$pdo->quote(File::STATUS_COMPLETE).',
                updated_at = now()
                where path = '.$pdo->quote($file['Key']).'
                and deleted_at is null
                limit 1';
        }

        $pdo->exec(implode(";\n\n", $queries));
    }
}
