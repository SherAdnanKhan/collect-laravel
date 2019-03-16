<?php

namespace App\Http\GraphQL\Mutations\Venue;

use App\Models\Venue;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Exceptions\AuthenticationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class Create
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

        try {
            $venue = Venue::create($input);
        } catch (\Exception $e) {
            throw new GenericException($e->getMessage());
        }

        return $venue;
    }
}
