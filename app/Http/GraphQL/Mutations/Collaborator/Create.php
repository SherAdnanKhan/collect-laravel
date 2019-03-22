<?php

namespace App\Http\GraphQL\Mutations\Collaborator;

use App\Models\Collaborator;
use App\Models\CollaboratorInvite;
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

        $cannotCreate = !$project || !$user->can('create', [
            Collaborator::class, $project, $userToAdd
        ]);

        if ($cannotCreate) {
            throw new AuthorizationException('User does not have permission to create a collaborator on this project');
        }

        $collaborator = $project->collaborators()->create($input);

        $this->createAndSendInvite($collaborator, array_get($input, 'email', ''), array_get($input, 'name', ''));

        return $collaborator;
    }

    private function createAndSendInvite(Collaborator $collaborator, $email, $name)
    {
        $invite = new CollaboratorInvite([
            'token'      => str_random(60),
            'project_id' => $collaborator->project->id,
            'name'       => $name,
            'email'      => $email,
        ]);

        // If they're inviting an existing user
        // we'll just use the values from that user.
        if ($collaborator->user) {
            $invite->name = $collaborator->user->name;
            $invite->email = $collaborator->user->email;
        }

        $saved = $collaborator->invite()->save($invite);

        if ($saved) {
            $invite->sendNotification();
        }
    }
}
