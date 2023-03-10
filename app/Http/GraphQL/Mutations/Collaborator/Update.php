<?php

namespace App\Http\GraphQL\Mutations\Collaborator;

use Log;
use App\Models\Collaborator;
use App\Models\CollaboratorInvite;
use App\Models\Recording;
use App\Models\Project;
use App\Models\User;
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
        $user = auth()->user();

        if (!$user->hasCollaboratorAccess()) {
            throw new AuthorizationException('User does not have the plan to access this functionality');
        }

        $input = array_get($args, 'input');
        $id = (int) array_get($input, 'id');
        $type = array_get($input, 'type', 'normal');

        $collaborator = Collaborator::find($id);

        if (!$collaborator) {
            throw new AuthorizationException('Collaborator could not be found');
        }

        // Check to see if the updated type is a normal.
        if ($type === 'normal') {
            // If we were a recording collab, remove
            // the associations to the recordings.
            if ($collaborator->type !== 'normal') {
                $collaborator->recordings()->sync([]);
            }

            // Update our type
            $collaborator->type = $type;
            $collaborator->save();

            return $collaborator;
        }

        $collaborator->type = $type;
        $collaborator->save();

        $recordingIds = [];
        if (array_has($input, 'recordings')) {
            $recordingIds = collect(array_get($input, 'recordings', []))->pluck('id')->toArray();
        }

        $project = $collaborator->project;

        $cannotUpdate = !$user->can('update', [
            Collaborator::class, $project, null
        ]);

        if ($cannotUpdate) {
            throw new AuthorizationException('User does not have permission to update a collaborator on this project');
        }

        if (empty($recordingIds)) {
            throw new GenericException('Missing recordings to save');
        }

        $collaborator->recordings()->sync($recordingIds);

        return $collaborator;
    }
}
