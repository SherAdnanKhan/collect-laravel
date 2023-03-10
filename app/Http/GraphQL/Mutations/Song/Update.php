<?php

namespace App\Http\GraphQL\Mutations\Song;

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
        $input = array_get($args, 'input');

        $song = Song::where('id', (int)array_get($input, 'id'))
            ->userUpdatable()
            ->first();

        if (!$song) {
            throw new AuthorizationException('Unable to find Song to update');
        }

        if ($song->iswc && empty($input['iswc'])) {
            unset($input['iswc']);
        }

        $folders = $song->folders();

        if ($folders->exists()) {
            $song->folders()->update(['name' => sprintf('Song: %s', array_get($input, 'title'))]);
        }

        $saved = $song->fill($input)->save();

        if (!$saved) {
            throw new GenericException('Error saving song');
        }

        return $song;
    }
}
