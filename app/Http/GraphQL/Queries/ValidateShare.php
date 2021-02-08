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
            'message' => 'success',
            'expired' => false,
            'url' => $url
        ];
    }
}
