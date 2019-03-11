<?php

namespace App\Http\GraphQL\Mutations\Collaborator;

use App\Models\Collaborator;
use App\Models\Project;
use App\Models\User;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Exceptions\AuthorizationException;
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
        $input = array_get($args, 'input');
        $userId = (int) array_get($input, 'user_id');
        $projectId = (int) array_get($input, 'project_id');

        if ($userId == $user->id) {
            throw new AuthorizationException('User cannot make themselves a collaborator');
        }

        $userToAdd = User::find($userId);
        $project = Project::find($projectId);

        $cannotCreate = !$userToAdd || !$project || !$user->can('create', [
            Collaborator::class, $project, $userToAdd
        ]);

        if ($cannotCreate) {
            throw new AuthorizationException('User does not have permission to create a collaborator on this project');
        }

        return $project->collaborators()->create($input);
    }
}