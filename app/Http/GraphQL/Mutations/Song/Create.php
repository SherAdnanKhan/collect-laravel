<?php

namespace App\Http\GraphQL\Mutations\Song;

use App\Models\Folder;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Exceptions\AuthenticationException;
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
        $input = array_get($args, 'input');
        $user = auth()->user();
        try {
            $song = $user->songs()->create($input);

            $folder = Folder::create([
                'user_id'    => $user->id,
                'name'       => sprintf('Song: %s', $song->title),
                'readonly'   => true
            ]);

            $song->folder_id = $folder->id;
            $song->save();
        } catch (\Exception $e) {
            throw new GenericException($e->getMessage());
        }

        return $song;
    }
}
