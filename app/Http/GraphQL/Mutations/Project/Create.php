<?php

namespace App\Http\GraphQL\Mutations\Project;

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
        $input = $args['input'];

        $user = auth()->user();

        if (!$user->can('create', Project::class)) {
            throw new AuthorizationException('The user does not have the ability to create a project');
        }

        // if (Project::where('number', 'like', $input['number'])->count() > 0) {
        //     throw new ValidationException('Project Number must be unique.', null, null, null, null, null, [
        //         'validation' => [
        //             'number' => ['Project Number must be unique.']
        //         ]
        //     ]);
        // }

        try {
            $project = $user->projects()->create($input);
        } catch (\Exception $e) {
            throw new GenericException($e->getMessage());
        }

        return $project;
    }
}
