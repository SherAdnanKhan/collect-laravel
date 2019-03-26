<?php

namespace App\Http\GraphQL\Mutations\UserProfile;

use GraphQL\Type\Definition\ResolveInfo;
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

        return $user->profile()->updateOrCreate([
            'user_id' => $user->id
        ], $input);
    }
}
