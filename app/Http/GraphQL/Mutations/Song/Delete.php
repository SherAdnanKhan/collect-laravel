<?php

namespace App\Http\GraphQL\Mutations\Song;

use App\Models\Song;
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

        $song = Song::where('id', (int) array_get($input, 'id'))
            ->userDeletable()
            ->first();

        if (!$song) {
            throw new AuthorizationException('Unable to find Song to delete');
        }

        $song->delete();

        return $song;
    }
}
