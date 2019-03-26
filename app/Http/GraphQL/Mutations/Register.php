<?php

namespace App\Http\GraphQL\Mutations;

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

        DB::beginTransaction();

        try {
            $user = new User();
            $user->fill(array_except($input, 'password_confirmation'));

            // Specify a user verification token, and that they're
            // inactive until verified.
            $user->verification_token = str_random(60);
            $user->status = 'inactive';

            $saved = $user->save();

            if (!$saved) {
                throw new GenericException('User was not created');
            }

            DB::commit();

            $user->sendRegistrationVerificationNotification();
        } catch (\Exception $e) {
            DB::rollback();

            throw $e;
        }

        return [
            'success' => true
        ];
    }
}
