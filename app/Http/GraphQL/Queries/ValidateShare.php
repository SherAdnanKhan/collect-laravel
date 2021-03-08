<?php

namespace App\Http\GraphQL\Queries;

use App\Models\Share;
use App\Models\ShareUser;
use Carbon\Carbon;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Validator;

class ValidateShare
{
    /**
     * @param $rootValue
     * @param array $args
     * @param \Nuwave\Lighthouse\Support\Contracts\GraphQLContext|null $context
     * @param \GraphQL\Type\Definition\ResolveInfo $resolveInfo
     * @return array
     */
    public function resolve($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {
        $uuid = $args['input']['uuid'];
        $encEmail = $args['input']['encryptedEmail'];
        $share = Share::find($uuid);
        $response = [
            'isPasswordRequired' => false,
            'success' => true,
            'errors' => [
                'isShareInvalid' => false,
                'isExpired' => false,
                'isSharePasswordRequired' => false,
                'isPasswordInvalid' => false,
                'isUserInvalid' => false,
            ],
            'url' => '',
        ];

        // Does this share exist yet?
        if (!$share || !$share->complete) {
            $response['success'] = false;
            $response['errors']['isShareInvalid'] = true;
            return $response;
        }

        // Has this share expired?
        if ($share->hasExpired()) {
            $response['success'] = false;
            $response['errors']['isExpired'] = true;
        }

        // Handle password, if necessary
        if (isset($share->password)) {
            $response['isPasswordRequired'] = true;
            $password = $args['input']['password'];
            if (!$password) {
                $response['success'] = false;
                $response['errors']['isPasswordInvalid'] = true;
            }
            if (!Hash::check($password, $share->password)) {
                $response['success'] = false;
                $response['errors']['isPasswordInvalid'] = true;
            }
        }

        // Is this user allowed access to this share?
        $shareUser = ShareUser::where([
            'encrypted_email' => $encEmail,
            'share_id' => $share->id
        ])->first();

        // This user is not allowed access to this share.
        if (!$shareUser) {
            $response['success'] = false;
            $response['errors']['isUserInvalid'] = true;
        }

        // Return if anything above has hindered success.
        if (!$response['success']) {
            return $response;
        }

        // Proceed with collecting share
        $shareUser->downloads()->create();

        $share->download_count = $share->download_count + 1;
        $share->save();

        $url = Storage::disk('s3')->temporaryUrl(
            substr($share->path, 1), Carbon::now()->addDay()
        );

        // Respond with full success
        $response['url'] = $url;
        return $response;
    }
}
