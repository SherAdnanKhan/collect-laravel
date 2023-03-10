<?php

namespace App\Http\GraphQL\Queries;

use App\Models\CollaboratorInvite;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Exceptions\AuthorizationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class GetInvites
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

        return CollaboratorInvite::whereHas('collaborator', function($query) use ($user) {
            return $query->where('email', 'like', $user->email)
                         ->where('accepted', 0);
        })->get();
    }
}
