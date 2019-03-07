<?php

namespace App\Http\GraphQL\Mutations\Session;

use App\Models\Song;
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
        $input = $args['input'];
        $id = (int) $input['id'];

        $song = Song::where('id', $id)->userUpdatable()->first();

        if (!$song) {
            throw new AuthorizationException('Unable to find Song to update');
        }

        $saved = $song->fill($input)->save();

        if (!$saved) {
            throw new GenericException('Error saving song');
        }

        return $song;
    }
}
