<?php

namespace App\Http\GraphQL\Mutations\Subscription;

use App\Http\GraphQL\Exceptions\ValidationException;
use App\Jobs\Emails\SendSubscriptionUpdatedEmail;
use App\Models\User;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Exceptions\AuthorizationException;
use Nuwave\Lighthouse\Exceptions\GenericException;
use Nuwave\Lighthouse\Execution\Utils\Subscription as GraphQLSubscription;
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
            throw new GenericException('Invalid plan chosen.');
        }

        $user = auth()->user();

        if (!$user->hasCardOnFile()) {
            throw new GenericException('User does not have a card on file.');
        }

        try {
            $subscription = $user->subscription(User::SUBSCRIPTION_NAME)->swap($plan);
        } catch (\Exception $e) {
            throw new GenericException('Unable to swap subscription.');
        }

        return $subscription->toArray();
    }
}
