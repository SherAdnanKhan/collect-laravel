<?php

namespace App\Http\GraphQL\Queries\Search;

use App\Models\Project;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Exceptions\AuthorizationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class Projects
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

        // We need the Project id's before
        $projectIds = Project::userViewable(['user' => $user])->pluck('id')->toArray();

        // The ElasticSearch Builder doesn't support our scopes.
        return Project::search($term)
            ->whereIn('id', $projectIds)
            ->take(10)
            ->get();
    }
}
