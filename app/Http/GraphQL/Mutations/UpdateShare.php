<?php

namespace App\Http\GraphQL\Mutations;

use App\Models\Share;
use Carbon\Carbon;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Exceptions\AuthorizationException;
use Nuwave\Lighthouse\Exceptions\GenericException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class UpdateShare
{
    /**
     * @param $rootValue
     * @param array $args
     * @param \Nuwave\Lighthouse\Support\Contracts\GraphQLContext|null $context
     * @param \GraphQL\Type\Definition\ResolveInfo $resolveInfo
     * @return array
     * @throws \Exception
     */
    public function resolve($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {
        $input = array_get($args, 'input');
        $response = [
            'success' => true,
            'data' => null,
            'errors' => [
                'isShareInvalid' => false,
                'isExpiryInvalid' => false,
                'isErrorUpdatingShare' => false,
            ]
        ];

        $share = Share::where('id', array_get($input, 'uuid'))
            ->with('user', 'project')
            ->userUpdatable()
            ->first();

        if (!$share) {
            $response['success'] = false;
            $response['errors']['isShareInvalid'] = true;
        }

        if (isset($input['expiry']) && !$this->isValidExpiry($input['expiry'])) {
            $response['success'] = false;
            $response['errors']['isExpiryInvalid'] = true;
        }

        $password = isset($input['password']) ? $input['password'] : null;
        $expiry = null;

        if (isset($input['expiry'])) {
            $expiry = $input['expiry'];
            $share->status = Share::STATUS_LIVE;
        }

        $share->password = (!empty($password)) ? bcrypt($password) : null;
        $share->expires_at = $expiry;
        $saved = $share->save();

        if (!$saved) {
            $response['success'] = false;
            $response['errors']['isErrorUpdatingShare'] = true;
        } else {
            $response['data'] = $share;
        }

        return $response;
    }

    private function isValidExpiry($expiry)
    {
        return Carbon::parse($expiry)->gt(Carbon::now());
    }
}
