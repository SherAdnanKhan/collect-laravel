<?php

namespace App\Http\GraphQL\Mutations\Collaborator;

use App\Models\Collaborator;
use App\Models\Project;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Exceptions\GenericException;
use Nuwave\Lighthouse\Exceptions\AuthorizationException;
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
        $authedUser = auth()->user();
        $input = array_get($args, 'input');
        $userId = (int) array_get($input, 'user_id');

        if ($userId == $authedUser->id) {
            throw new AuthorizationException('User cannot make themselves a collaborator');
        }

        if (!$user->can('create', [Collaborator::class, $project])) {
            throw new AuthorizationException('User does not have permission to create a collaborator on this project');
        }

        return $project->collaborators()->create($input);
    }
}
