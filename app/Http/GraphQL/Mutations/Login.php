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
            $token = str_random(32);
            $expiry = 5 * 60;

            $code = User::generateTwoFactorCode();
            $payload = [
                'user'  => $user->getKey(),
                'code'  => $code,
                'phone' => $user->phone,
            ];

            // We'll cache it.
            Cache::put('2fa.token.' . $token, $payload, $expiry);

            // Trigger the job to send the SMS.
            SendTwoFactorSMS::dispatch($user->phone, $code);

            // And we'll send back a different payload so
            // the client knows we're about to do 2fa.
            return [
                'access_token' => $token,
                'token_type'   => 'token',
                'expires_in'   => $expiry,
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
        } catch (JWTException $e) {
            return [
                'access_token' => '',
                'token_type'   => 'bearer',
                'expires_in'   => 0
            ];
        }
    }

    /**
     * @param $rootValue
     * @param array $args
     * @param \Nuwave\Lighthouse\Support\Contracts\GraphQLContext|null $context
     * @param \GraphQL\Type\Definition\ResolveInfo $resolveInfo
     * @return array
     * @throws \Exception
     */
    public function twoFactor($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {
        $token = array_get($args, 'input.token');
        $cacheKey = '2fa.token.' . $token;
        $code = array_get($args, 'input.code');

        if (!Cache::has($cacheKey)) {
            throw new AuthenticationException('2FA token provided is invalid');
        }

        $payload = Cache::get($cacheKey);
        $user = User::find((int) $payload['user']);

        if (!$user || !$user->requiresTwoFactor()) {
            throw new AuthenticationException('2FA token provided is invalid');
        }

        if ($code != $payload['code']) {
            throw new AuthenticationException('2FA code is invalid');
        }

        Cache::delete($cacheKey);

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
    public function twoFactorResend($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {
        $token = array_get($args, 'input.token');
        $cacheKey = '2fa.token.' . $token;

        if (!Cache::has($cacheKey)) {
            throw new AuthenticationException('2FA token provided is invalid');
        }

        $payload = Cache::get($cacheKey);

        // Trigger the job to send the SMS.
        SendTwoFactorSMS::dispatch($payload['phone'], $payload['code']);

        // Re-add the paylaod to the cache so that it increases the expiry
        Cache::put($cacheKey, $payload, 5 * 60);

        return [
            'resent' => true,
        ];
    }
}
