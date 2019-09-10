<?php

namespace App\Http\GraphQL\Mutations\Collaborator;

use App\Models\Collaborator;
use App\Models\CollaboratorInvite;
use App\Models\Recording;
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

        if (!$user->hasCollaboratorAccess()) {
            throw new AuthorizationException('User does not have the plan to access this functionality');
        }

        $input = array_get($args, 'input');
        $projectId = (int) array_get($input, 'project_id');

        $project = Project::find($projectId);

        if (!$project) {
            throw new AuthorizationException('Project does not exist');
        }

        $recordingIds = [];
        if (array_has($input, 'recordings')) {
            $recordingIds = collect(array_get($input, 'recordings', []))->pluck('id')->toArray();
        }

        $cannotCreate = !$user->can('create', [
            Collaborator::class, $project, null
        ]);

        if ($cannotCreate) {
            throw new AuthorizationException('User does not have permission to create a collaborator on this project');
        }

        $collaborator = $project->collaborators()->create(array_except($input, ['recordings']));

        if (!empty($recordingIds)) {
            $collaborator->recordings()->attach($recordingIds);
        }

        return $collaborator;
    }
}
