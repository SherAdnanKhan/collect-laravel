<?php

namespace App\Http\Controllers\Webhooks;

use App\Models\File;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Auth\AuthenticationException;

/**
 * Handle lambda webhook requests.
 */
class LambdaController extends Controller
{
    public function updateFileInfo(Request $request)
    {
        $user = auth()->user();
        if ($user->name !== 'Lambda') {
            Log::error(sprintf('User %s is not authenticated to hit the lambda webhook'. $user->name));
            throw new AuthenticationException;
        }

        // Only way I could get this to work
        // was to grab the underlying PDO driver
        // and run it directly...
        $pdo = app('db')->getPdo();
        $queries = [];
        $query_data = [];

        foreach ($request->input('Files') as $file_i => $file) {
            Log::info('Updating file info for ' . $file['Key'], [
                'info' => $file,
            ]);

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
