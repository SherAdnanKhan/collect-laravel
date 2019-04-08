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
class UpdateBilling
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
        $stripeToken = array_get($args, 'input.stripe_token');

        $user = auth()->user();

        try {
            // If the user doesn't have a sub, just put them on the free plan
            // and then we can assign the card.
            if (!$user->subscription(User::SUBSCRIPTION_NAME)) {
                $user->newSubscription(User::SUBSCRIPTION_NAME, User::DEFAULT_SUBSCRIPTION_PLAN)->create(null, [
                    'email' => $user->email,
                ]);
            }

            $user->updateCard($stripeToken);
        } catch (\Exception $e) {
            throw new GenericException('Something went wrong when updating the customers card.');
        }

        return $user->toArray();
    }
}
