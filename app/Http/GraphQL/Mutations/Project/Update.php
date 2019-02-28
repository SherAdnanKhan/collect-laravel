<?php

namespace App\Http\GraphQL\Mutations\Project;

use App\Models\Project;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Exceptions\AuthenticationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class Update
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
        $input = $args['input'];
        $id = (int) $input['id'];

        $project = Project::where('id', $id)->userViewable()->first();

        if (!$project) {
            throw new \Exception('Unable to find project to update');
        }

        $saved = $project->fill($input)->save();

        if (!$saved) {
            throw new \Exception('Error saving project');
        }

        return $project;
    }
}
