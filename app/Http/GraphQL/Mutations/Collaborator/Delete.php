<?php

namespace App\Http\GraphQL\Mutations\Collaborator;

use App\Models\Collaborator;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Exceptions\AuthorizationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class Delete
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

        $collaborator = Collaborator::where('id', (int) array_get($args, 'input.id'))
            ->userDeletable()
            ->first();

        if (!$collaborator) {
            throw new AuthorizationException('Unable to find collaborator to delete');
        }

        $collaborator->delete();

        return $collaborator;
    }
}
