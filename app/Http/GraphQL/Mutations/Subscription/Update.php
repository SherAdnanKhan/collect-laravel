<?php

namespace App\Http\GraphQL\Mutations\Subscription;

use App\Http\GraphQL\Exceptions\ValidationException;
use App\Models\User;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Exceptions\AuthorizationException;
use Nuwave\Lighthouse\Exceptions\GenericException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * Handle the deletion of files.
 */
class Update
{
    /**
     * @param $rootValue
     * @param array $args
     * @param \Nuwave\Lighthouse\Support\Contracts\GraphQLContext|null $context
     * @param \GraphQL\Type\Definition\ResolveInfo $resolveInfo
     * @return array
     * @throws \AuthorizationException
     */
    public function resolve(
        $rootValue,
        array $args,
        GraphQLContext $context = null,
        ResolveInfo $resolveInfo
    ): array
    {
        $plan = array_get($args, 'input.plan');

        if (!in_array($plan, User::PLANS)) {
            throw new AuthorizationException('Invalid plan chosen.');
        }

        $user = auth()->user();

        if (!$user->hasCardOnFile()) {
            throw new AuthorizationException('User does not have a card on file.');
        }

        $subscription = $user->subscription(User::SUBSCRIPTION_NAME)->swap($plan);

        return $subscription->toArray();
    }
}
