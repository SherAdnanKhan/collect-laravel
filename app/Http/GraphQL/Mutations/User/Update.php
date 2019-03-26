<?php

namespace App\Http\GraphQL\Mutations\User;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Exceptions\AuthenticationException;
use Nuwave\Lighthouse\Exceptions\AuthorizationException;
use Nuwave\Lighthouse\Exceptions\GenericException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class Update
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
        $user = auth()->user();

        if (array_key_exists('password', $input)) {
            if (!array_key_exists('current_password', $input)) {
                throw new AuthorizationException('Current password must be provided if updating the users password');
            }

            $authed = auth()->attempt([
                'email'    => $user->email,
                'password' => array_get($input, 'current_password'),
            ]);

            if (!$authed) {
                throw new AuthenticationException('User is not authenticated when attempting to update their password');
            }
        }

        $data = array_except($input, ['password_confirmation']);

        try {
            $user->fill($data)->save();
        } catch (\Exception $e) {
            throw new GenericException($e->getMessage());
        }

        return $user;
    }
}
