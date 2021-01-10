<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Jobs\Emails\SendShareZipCreatedEmail;
use App\Models\Share;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Handle lambda webhook requests.
 */
class ShareZipGeneratorController extends Controller
{
    public function complete(Request $request)
    {
        $user = auth()->user();
        if ($user->name !== 'ShareZipGenerator') {
            Log::error(sprintf('User %s is not authenticated to hit the share zip webhook'. $user->name));
            throw new AuthenticationException;
        }

        $userId = $request->get('userId');
        $shareId = $request->get('shareId');
        $fileName = $request->get('fileName');
        $refresh = $request->get('refresh');

        $user = User::find($userId);
        if (!$user || $user->status !== 'active') {
            Log::error('User could not be found or is not active', [
                'fileName' => $fileName,
                'userId' => $userId,
                'user' => $user,
            ]);

            return;
        }

        $share = Share::find($shareId);
        if ($share->complete && !$request->refresh) {
            Log::error(sprintf('The share is already complete: %s', $share->id));
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

        $share->update([
            'size' => $size,
            'path' => $fileName,
            'complete' => true,
            'status' => Share::STATUS_LIVE
        ]);

        //Dont send email if refresh is set to true - which means just updating the expired path
        if (!$refresh) {
            SendShareZipCreatedEmail::dispatch($share);
        }
    }
}
