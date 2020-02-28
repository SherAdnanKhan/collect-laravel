<?php

namespace App\Http\GraphQL\Queries\Search;

use App\Models\Project;
use App\Models\Session;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Nuwave\Lighthouse\Exceptions\AuthorizationException;

class Sessions
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

        $sessionIds = Session::userViewable(['user' => $user])->pluck('id')->toArray();

        return Session::search($term)
            ->whereIn('id', $sessionIds)
            ->take(10)
            ->get();
    }
}
