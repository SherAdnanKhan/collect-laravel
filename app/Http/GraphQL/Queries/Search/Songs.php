<?php

namespace App\Http\GraphQL\Queries\Search;

use App\Models\Song;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Exceptions\AuthorizationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class Songs
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
        $term = array_get($args, 'term');
        $user = auth()->user();

        $songIds = Song::userViewable(['user' => $user])->pluck('id')->toArray();

        return Song::search($term)
            ->whereIn('id', $songIds)
            ->take(10)
            ->get();
    }
}
