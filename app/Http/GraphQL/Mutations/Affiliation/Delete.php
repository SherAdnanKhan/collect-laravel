<?php

namespace App\Http\GraphQL\Mutations\Affiliation;

use App\Models\UserAffiliation;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Exceptions\AuthorizationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class Delete
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
        $affiliation = UserAffiliation::where('id', (int) array_get($args, 'input.id'))
            ->first();

        if (!$affiliation) {
            throw new AuthorizationException('Unable to find affiliation to delete');
        }

        $affiliation->delete();

        return $affiliation;
    }
}
