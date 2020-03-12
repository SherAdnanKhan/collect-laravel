<?php

namespace App\Http\GraphQL\Mutations\CollaboratorInvite;

use App\Models\CollaboratorInvite;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Log;
use Nuwave\Lighthouse\Exceptions\AuthorizationException;
use Nuwave\Lighthouse\Execution\Utils\Subscription;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class Accept
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

        $invite = CollaboratorInvite::where('token', array_get($input, 'token'))->whereHas('collaborator', function($query) use($user) {
            return $query->where('email', 'like', $user->email);
        })->first();

        if (!$invite) {
            throw new AuthorizationException('Invite token is invalid');
        }

        $collaborator = $invite->collaborator;
        $collaborator->accepted = true;

        $collaborator->user_id = $user->id;
        $collaborator->save();

        $invite->delete();

        $collaborator->refresh();

        // Broadcast a GraphQL subscription for clients.
        Log::debug('userPermissionsUpdated', [$collaborator->user]);
        Subscription::broadcast('userPermissionsUpdated', $collaborator->user);

        return $collaborator;
    }
}
