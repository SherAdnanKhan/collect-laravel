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
        $success = true;
        $isPasswordRequired = false;
        $errors = [
            'isShareInvalid' => false,
            'isExpired' => false,
            'isSharePasswordRequired' => false,
            'isPasswordInvalid' => false,
            'isUserInvalid' => false,
        ];

        if (!$share || !$share->complete) {
            $success = false;
            $errors['isShareInvalid'] = true;
            return [
                'success' => false,
                'errors' => $errors,
                'isPasswordRequired' => $isPasswordRequired
            ];
        }

        if ($share->hasExpired()) {
            $success = false;
            $errors['isExpired'] = true;
        }

        if (isset($share->password)) {
            $isPasswordRequired = true;
            $password = $args['input']['password'];
            if (!$password) {
                $success = false;
                $errors['isPasswordInvalid'] = true;
            }
            if (!Hash::check($password, $share->password)) {
                $success = false;
                $errors['isPasswordInvalid'] = true;
            }
        }

        $shareUser = ShareUser::where(['encrypted_email' => $encEmail, 'share_id' => $share->id])->first();

        if (!$shareUser) {
            $success = false;
            $errors['isUserInvalid'] = true;
        }

        if (!$success) {
            return [
                'success' => false,
                'errors' => $errors,
                'isPasswordRequired' => $isPasswordRequired
            ];
        }

        $shareUser->downloads()->create();

        $share->download_count = $share->download_count + 1;
        $share->save();

        if (isset($share->expires_at)) {
            $url = Storage::disk('s3')->temporaryUrl(
                substr($share->path, 1), Carbon::now()->addDay()
            );
        } else {
            $url = Storage::disk('s3')->url(substr($share->path, 1));
        }

        return [
            'success' => true,
            'url' => $url,
            'isPasswordRequired' => $isPasswordRequired
        ];
    }
}
