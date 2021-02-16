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

        if (!$share || !$share->complete) {
            return [
                'success' => false,
                'errors' => [ 'isShareInvalid' => true ]
            ];
        }

        if ($share->hasExpired()) {
            return [
                'success' => false,
                'errors' => [ 'isShareExpired' => true ]
            ];
        }

        if (isset($share->password)) {
            $password = $args['input']['password'];
            if (!$password) {
                return [
                    'success' => false,
                    'errors' => [ 'isSharePasswordRequired' => true ]
                ];
            }
            if (!Hash::check($password, $share->password)) {
                return [
                    'success' => false,
                    'errors' => [ 'isSharePasswordInvalid' => true ]
                ];
            }
        }

        $shareUser = ShareUser::where(['encrypted_email' => $encEmail, 'share_id' => $share->id])->first();

        if (!$shareUser) {
            return [
                'success' => false,
                'errors' => [ 'isShareUserInvalid' => true ]
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
            'url' => $url
        ];
    }
}
