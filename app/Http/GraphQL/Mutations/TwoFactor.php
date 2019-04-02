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

class TwoFactor
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
        $token = array_get($args, 'input.token');
        $code = array_get($args, 'input.code');

        $twoFactor = resolve('App\Util\TwoFactorAuthentication');
        if (!$twoFactor->validate($token, $code)) {
            throw new AuthenticationException;
        }

        $payload = $twoFactor->getPayload($token);
        $user = User::find(array_get($payload, 'user', false));

        $meta = array_get($payload, 'meta', []);

        // Is this from a user update?
        $userUpdate = array_get($payload, 'update_user', false);

        // If we've come from the user update.
        if ($userUpdate) {
            // Update the users phone on valid 2FA to be the phone we've
            // authenticated via SMS
            $phone = array_get($payload, 'phone');
            if (is_null($user->phone) || $phone != $user->phone) {
                $user->phone = $phone;
            }

            $user->two_factor_enabled = true;
            $user->save();
        }

        $twoFactor->finish($token);

        // If we've just authed a user update, no need to
        // generate an auth token, we'll just say it
        // was authenticated.
        if ($userUpdate) {
            return [
                'token_type'    => '2fa',
                'authenticated' => true,
            ];
        }

        // If this is a full on login request,
        // we'll generate a token and return the normal
        // 2fa login payload.
        $token = auth()->fromUser($user);

        if (!$token) {
            throw new AuthenticationException;
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
    public function resend($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {
        $token = array_get($args, 'input.token');

        $twoFactor = resolve('App\Util\TwoFactorAuthentication');
        $twoFactor->fromToken($token)->send();

        return [
            'resent' => true,
        ];
    }
}
