<?php

namespace App\Http\GraphQL\Queries\Search;

use App\Models\File;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Exceptions\AuthorizationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class ProjectFiles
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

        $project_ids = $user->projects()->userViewable(['user' => $user])->pluck('id')->toArray();

        return File::search($term)
            ->whereIn('project_id', $project_ids)
            ->take(10)
            ->get();
    }
}
