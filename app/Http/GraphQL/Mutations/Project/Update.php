<?php

namespace App\Http\GraphQL\Mutations\Project;

use App\Http\GraphQL\Exceptions\ValidationException;
use App\Models\Project;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Exceptions\AuthorizationException;
use Nuwave\Lighthouse\Exceptions\GenericException;
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

        $project = Project::where('id', $id)->userUpdatable()->first();

        if (!$project) {
            throw new AuthorizationException('Unable to find project to update');
        }

        if ($input['number'] != $project->number) {
            if (Project::where('number', 'like', $project->number)->count() > 0) {
                throw new ValidationException('Project Number must be unique.', null, null, null, null, null, [
                    'validation' => [
                        'number' => ['Project Number must be unique.']
                    ]
                ]);
            }
        }

        $saved = $project->fill($input)->save();

        if (!$saved) {
            throw new GenericException('Error saving project');
        }

        return $project;
    }
}
