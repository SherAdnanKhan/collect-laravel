<?php

namespace App\Http\GraphQL\Mutations\CollaboratorInvite;

use App\Models\Collaborator;
use App\Models\CollaboratorInvite;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Nuwave\Lighthouse\Exceptions\AuthorizationException;

class Resend
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
        $input = array_get($args, 'input');
        $user = auth()->user();

        $collaborator = Collaborator::find(array_get($input, 'collaboratorId'));

        if (!$collaborator) {
            throw new AuthorizationException('No collaborator found');
        }

        $invite = $collaborator->invite;

        if (!$invite) {
            throw new AuthorizationException('No collaborator invite found');
        }

        $invite->sendNotification();

        return [
            'success' => true,
        ];
    }
}
