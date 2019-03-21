<?php

namespace App\Http\GraphQL\Queries\Credit;

use App\Models\Credit;
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
        $projectId = (int) array_get($args, 'projectId');

        $project = Project::where('id', $projectId)
            ->with('credits')
            ->userViewable()
            ->first();

        if (!$project) {
            throw new AuthorizationException('The user does not have access to view this projects credits');
        }

        $creditsQuery = $project->credits();

        if (array_key_exists('contributionType', $args) && in_array(array_get($args, 'contributionType'), Credit::TYPES)) {
            $creditsQuery = $creditsQuery->where('contribution_type', array_get($args, 'contributionType'));
        }

        return $creditsQuery->get();
    }
}
