<?php

namespace App\Http\GraphQL\Mutations\Comment;

use App\Models\Comment;
use App\Models\Project;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Exceptions\GenericException;
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
        $user = auth()->user();
        $projectId = (int) array_get($args, 'input.project_id');

        $project = Project::find($projectId);

        if (!$project) {
            throw new AuthorizationException('Unable to find project to associate comment to');
        }

        if (!$user->can('create', [Comment::class, $project])) {
            throw new AuthorizationException('User does not have permission to create a comment on this project');
        }

        return $project->comments()->create(array_get($args, 'input'));
    }
}
