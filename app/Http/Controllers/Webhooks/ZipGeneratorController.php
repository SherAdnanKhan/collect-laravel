<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Models\User;
use \Illuminate\Http\Request;

/**
 * Handle lambda webhook requests.
 */
class ZipGeneratorController extends Controller
{
    public function complete(Request $request)
    {
        $user = auth()->user();
        if ($user->name !== 'ZipGenerator') {
            throw new AuthenticationException;
        }

        $userId = $request->get('userId');
        $fileName = $request->get('fileName');

        $user = User::find($userId);
        if (!$user || $user->status !== 'active') {
            return;
        }

        if (strpos($fileName, "/downloads/" . $user->id . "/") !== 0) {
            return;
        }

        SendZipCreatedEmail::dispatch($user, $fileName);
    }
}
