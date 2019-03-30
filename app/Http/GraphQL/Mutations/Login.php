<?php

namespace App\Http\GraphQL\Mutations;

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
            $expiry = 5*60;

            // We'll cache it.
            Cache::put('2fa.token.' . $token, $user->id, $expiry);

            $client = resolve('Nexmo\Client');

            if (!is_null($user->two_factor_verification_id)) {
                $client->verify()->cancel($user->two_factor_verification_id);
            }

            $verification = $client->verify()->start([
                'number'      => $user->phone,
                'brand'       => config('services.nexmo.from'),
                'code_length' => config('services.nexmo.code_length', '6'),
            ]);

            $user->two_factor_verification_id = $verification->getRequestId();
            Log::debug($user->two_factor_verification_id);
            $user->save();

            // And we'll send back a different payload so
            // the client knows we're about to do 2fa.
            return [
                'access_token' => $token,
                'token_type'   => 'token',
                'expires_in'   => 5 * 60,
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
        $code = array_get($args, 'input.code');

        if (!Cache::has('2fa.token.' . $token)) {
            throw new AuthenticationException('2FA token provided is invalid');
        }

        $userId = Cache::get('2fa.token.' . $token);
        $user = User::find($userId);

        if (!$user || !$user->requiresTwoFactor()) {
            throw new AuthenticationException('2FA token provided is invalid');
        }

        $client = resolve('Nexmo\Client');
        $verification = new \Nexmo\Verify\Verification($user->two_factor_verification_id);

        try {
            $client->verify()->check($verification, $code);
            $client->verify()->cancel($user->two_factor_verification_id);
        } catch (\Exception $e) {
            throw new AuthenticationException('2FA Authentication failed.');
        }

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
}
