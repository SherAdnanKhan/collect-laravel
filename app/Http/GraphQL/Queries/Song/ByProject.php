<?php

namespace App\Http\GraphQL\Queries\Song;

use App\Models\Project;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Exceptions\AuthorizationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class ByProject
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

        $project = Project::with(['recordings', 'recordings.song'])->where('id', $projectId)->first();

        if (!$project) {
            throw new AuthorizationException('Unable to find to fetch songs for');
        }

        return $project->recordings->map(function($recording) {
            return $recording->song;
        })->all();
    }
}
