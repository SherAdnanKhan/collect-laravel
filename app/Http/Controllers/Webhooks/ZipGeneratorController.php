<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Jobs\Emails\SendZipCreatedEmail;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

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

        if (strpos($fileName, "/downloads/" . $user->id . "/") !== 0) {
            Log::error('The download path is not in the file name', [
                'fileName' => $fileName,
                'userId' => $userId,
                'path' => "/downloads/" . $user->id . "/",
            ]);
            return;
        }

        SendZipCreatedEmail::dispatch($user, $fileName);
    }
}
