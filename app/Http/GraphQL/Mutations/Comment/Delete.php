<?php

namespace App\Http\GraphQL\Mutations\Comment;

use App\Models\Comment;
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
        $comment = auth()->user()
            ->comments()
            ->where('id', (int) array_get('input.id', $args))
            ->first();

        if (!$comment) {
            throw new AuthorizationException('Unable to find comment to delete');
        }

        return $comment->delete();
    }
}
