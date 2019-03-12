<?php

namespace App\Http\GraphQL\Queries\Song;

use App\Models\Project;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Exceptions\AuthorizationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class SongByProject
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
        $projectId = (int) $args['projectId'];

        $project = Project::find($projectId);

        if (!$project) {
            throw new AuthorizationException('Unable to find to fetch songs for');
        }
        // dd($project->songs()->get());
        return $project->songs()->get();
    }
}
