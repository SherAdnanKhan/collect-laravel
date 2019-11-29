<?php

namespace App\Http\GraphQL\Mutations\CollaboratorInvite;

use App\Models\CollaboratorInvite;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Exceptions\AuthorizationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

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

        $invite = CollaboratorInvite::where('id', array_get($input, 'collaboratorId'))
            ->where('user_id', null)
            ->where('accepted', 0)
            ->first();

        if (!$invite) {
            throw new AuthorizationException('No collaborator invite found');
        }

        $invite->sendNotification();

        return [
            'success' => true,
        ];
    }
}
