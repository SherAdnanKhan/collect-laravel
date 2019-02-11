<?php

namespace App\Http\GraphQL\Mutations;

use App\Models\User;
use App\Models\UserFavourite;
use Firebase\JWT\JWT;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Http\Request;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class UserFavouriteMutator
{
    /**
     * Resolve the create mutation for a User Favourite.
     *
     * @param  mixed          $root
     * @param  array          $args
     * @param  GraphQLContext $context
     * @param  ResolveInfo    $resolve_info
     * @return array
     */
    public function create($root, array $args, GraphQLContext $context, ResolveInfo $resolve_info) : array
    {
        throw_if(!isset($args['input']), new \Exception('The input argument is missing.'));
        $input = array_get($args, 'input');

        throw_if(!isset($input['user']), new \Exception('The user is missing.'));
        $user = User::findOrFail((int) array_get($input, 'user.id'));

        $favourite = $user->favourites()->save(new UserFavourite(array_only($input, [
            'resourceId', 'resourceType',
        ])));

        return $favourite;
    }
}
