<?php

namespace App\Http\GraphQL\Mutations\Venue;

use App\Models\Venue;
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
        $input = array_get($args, 'input');

        $venue = Venue::where('id', (int) array_get($input, 'id'))->first();

        if (!$venue) {
            throw new AuthorizationException('Unable to find venue to delete');
        }

        $venue->delete();

        return $venue;
    }
}
