<?php

namespace App\Http\GraphQL\Mutations\User;

use App\Http\GraphQL\Mutations\Login;
use App\Models\User;
use App\Models\UserProfile;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\DB;
use Nuwave\Lighthouse\Exceptions\GenericException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * Handle the logic to register a new user.
 */
class ResendVerificationEmail
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
        $user = User::where('email', 'like', $input['email'])->whereNotNull('verification_token')->first();
        if (!$user) {
            return [
                'success' => false
            ];
        }

        $user->sendRegistrationVerificationNotification();

        return [
            'success' => true
        ];
    }
}
