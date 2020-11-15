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
        $encEmail = $args['input']['encrypted_email'];

        $share = Share::find($uuid);

        if (!$share || $share->hasExpired() || !$share->complete) {
            return [
                'message' => 'failure',
                'expired' => true
            ];
        }

        if (isset($share->password)) {
            $password = $args['input']['password'];
            if (!Hash::check($password, $share->password)) {
                return [
                    'message' => 'Invalid Password!',
                    'expired' => true
                ];
            }
        }

        $shareUser = ShareUser::where(['encrypted_email' => $encEmail, 'share_id' => $share->id])->first();

        if (!$shareUser) {
            return [
                'message' => 'Invalid Reference!',
                'expired' => true
            ];
        }

        $shareUser->download_count = $shareUser->download_count + 1;
        $shareUser->downloaded_last_at = Carbon::now();
        $shareUser->save();

        $share->download_count = $share->download_count + 1;
        $share->save();

        $url = Storage::disk('s3')->temporaryUrl(
            substr($share->path, 1), $share->expires_at
        );

        return [
            'message' => 'success',
            'expired' => false,
            'url' => $url
        ];
    }
}
