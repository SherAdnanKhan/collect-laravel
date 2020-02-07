<?php

namespace App\Http\GraphQL\Mutations;

use App\Jobs\SMS\SendTwoFactorSMS;
use App\Models\User;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Nuwave\Lighthouse\Exceptions\AuthenticationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Tymon\JWTAuth\Facades\JWTAuth;

class Login
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
        $credentials = array_merge(['status' => 'active'], array_get($args, 'input'));
        $token = auth()->attempt($credentials);

        if (!$token) {
            throw new AuthenticationException;
        }

        $user = auth()->user();
        if ($user->requiresTwoFactor()) {
            // We'll create a new token which we'll use to auth the
            // request to verify the 2fa code.
            $twoFactor = resolve('App\Util\TwoFactorAuthentication');
            $token = $twoFactor->setPhone($user->phone)->setUser($user)->send();

            // And we'll send back a different payload so
            // the client knows we're about to do 2fa.
            return [
                'access_token' => $token,
                'token_type'   => '2fa',
                'expires_in'   => $twoFactor->getExpiry(),
                'two_factor'   => true,
            ];
        }

        return [
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth()->factory()->getTTL() * 60,
            'two_factor'   => false,
        ];
    }

    /**
     * @param $rootValue
     * @param array $args
     * @param \Nuwave\Lighthouse\Support\Contracts\GraphQLContext|null $context
     * @param \GraphQL\Type\Definition\ResolveInfo $resolveInfo
     * @return array
     * @throws \Exception
     */
    public function refresh($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {
        try {
            $refreshed = JWTAuth::refresh(JWTAuth::getToken());

            return [
                'access_token' => $refreshed,
                'token_type'   => 'bearer',
                'expires_in'   => auth()->factory()->getTTL() * 60
            ];
        } catch (\Exception $e) {
            throw new AuthenticationException('Unable to refresh the token');
        }
    }
}
