<?php

namespace App\Http\GraphQL\Mutations;

use App\Http\GraphQL\Mutations\Login;
use App\Models\User;
use App\Models\UserProfile;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Nuwave\Lighthouse\Exceptions\AuthorizationException;
use Nuwave\Lighthouse\Exceptions\GenericException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * Handle the logic to register a new user.
 */
class Register
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

        if (!in_array($input['plan'], User::PLANS)) {
            throw new AuthorizationException('Invalid plan chosen.');
        }

        DB::beginTransaction();

        try {
            $user = new User();
            $user->fill(array_except($input, ['password_confirmation', 'plan', 'stripe_token']));

            // Specify a user verification token, and that they're
            // inactive until verified.
            $user->verification_token = str_random(60);
            $user->status = 'inactive';

            $saved = $user->save();

            if (!$saved) {
                throw new GenericException('User was not created');
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            throw $e;
        }

        try {
            $subscription = $user->newSubscription(User::SUBSCRIPTION_NAME, $input['plan'])->create($input['stripe_token'] ? $input['stripe_token'] : null, [
                'email' => $user->email,
            ]);
        } catch (\Exception $e) {
            Log::error('Could not create stripe subscription', $e);

            try {
                $user->asStripeCustomer()->delete();
            } catch (\Exception $e) {
                Log::error('Could not delete stripe customer', $e);
            }
            $user->forceDelete();

            return [
                'success' => false
            ];
        }

        $user->sendRegistrationVerificationNotification();
        $user->sendNewSubscriptionEmail($subscription);

        return [
            'success' => true
        ];
    }
}
