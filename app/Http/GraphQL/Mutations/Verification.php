<?php

namespace App\Http\GraphQL\Mutations;

use App\Models\User;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Exceptions\AuthenticationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class Verification
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

        $user = User::where('verification_token', array_get($input, 'token'))
            ->where('email', 'like', array_get($input, 'email'))
            ->where('status', 'inactive')
            ->first();

        if (!$user) {
            throw new AuthenticationException('User is not verified.');
        }

        $token = auth()->fromUser($user);

        if (!$token) {
            throw new AuthenticationException('Unable to generate token.');
        }

        $user->verification_token = null;
        $user->status = 'active';
        $user->save();

        $subscription = $user->subscription(User::SUBSCRIPTION_NAME);
        if ($subscription->stripe_plan != User::PLAN_PRO && $subscription->stripe_plan != User::PLAN_PRO_UNLIMITED) {
            $user->sendWelcomeNotification();
        }

        return [
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth()->factory()->getTTL() * 60
        ];
    }
}
