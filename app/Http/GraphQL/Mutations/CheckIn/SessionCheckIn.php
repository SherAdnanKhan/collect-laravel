<?php

namespace App\Http\GraphQL\Mutations;

use App\Models\User;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Exceptions\AuthenticationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class SessionCheckIn
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
        // TODO:
        // Given the session and some profile information we'll create a party and a credit against
        // the session, if the party already exists against this session we'll use that. (matching on email and name?)

        // TODO: Return a payload valid with this GraphQL type.
        // type SessionCheckinPayload {
        //     success: Boolean!
        // }

        return [];
    }
}
