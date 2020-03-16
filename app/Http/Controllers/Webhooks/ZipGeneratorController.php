<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Jobs\Emails\SendZipCreatedEmail;
use App\Models\DownloadJob;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Handle lambda webhook requests.
 */
class ZipGeneratorController extends Controller
{
    public function complete(Request $request)
    {
        $user = auth()->user();
        if ($user->name !== 'ZipGenerator') {
            Log::error(sprintf('User %s is not authenticated to hit the zip webhook'. $user->name));
            throw new AuthenticationException;
        }

        $userId = $request->get('userId');
        $downloadJobId = $request->get('downloadJobId');
        $fileName = $request->get('fileName');

        $user = User::find($userId);
        if (!$user || $user->status !== 'active') {
            Log::error('User could not be found or is not active', [
                'fileName' => $fileName,
                'userId' => $userId,
                'user' => $user,
            ]);

            return;
        }

        $download_job = DownloadJob::find($downloadJobId);
        if ($download_job->complete) {
            Log::error(sprintf('The download job is already complete: %s', $download_job->id));
            return;
        }

        if (strpos($fileName, "/downloads/" . $user->id . "/") !== 0) {
            Log::error('The download path is not in the file name', [
                'fileName' => $fileName,
                'userId' => $userId,
                'path' => "/downloads/" . $user->id . "/",
            ]);
            return;
        }

        $size = Storage::disk('s3')->size(substr($fileName, 1));

        $download_job->update([
            'size' => $size,
            'path' => $fileName,
            'complete' => true,
            'expires_at' => Carbon::now()->addDay(),
        ]);

        SendZipCreatedEmail::dispatch($download_job);
    }
}
