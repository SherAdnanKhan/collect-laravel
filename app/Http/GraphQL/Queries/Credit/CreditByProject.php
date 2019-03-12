<?php

namespace App\Http\GraphQL\Queries\Credit;

use App\Models\Project;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Exceptions\AuthorizationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class CreditByProject
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

        $project = Project::where('id', $projectId)
            ->with('credits')
            ->userViewable()
            ->first();

        if (!$project) {
            throw new AuthorizationException('The user does not have access to view this projects credits');
        }

        return $project->credits;
    }
}
