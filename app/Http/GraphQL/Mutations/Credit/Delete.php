<?php

namespace App\Http\GraphQL\Mutations\Credit;

use App\Models\Credit;
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
        $credit = Credit::where('id', (int) array_get($args, 'input.id'))
            ->userDeletable()
            ->first();

        if (!$credit) {
            throw new AuthorizationException('Unable to find credit to delete');
        }

        $credit->delete();

        return $credit;
    }
}
