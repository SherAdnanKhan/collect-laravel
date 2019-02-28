<?php

namespace App\Http\GraphQL\Mutations\UserFavourite;

use App\Models\File;
use App\Models\Person;
use App\Models\Project;
use App\Models\Recording;
use App\Models\Session;
use App\Models\Song;
use App\Models\UserFavourite;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Exceptions\AuthenticationException;
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
        $input = $args['input'];

        $types = [
            'file'      => File::class,
            'project'   => Project::class,
            'session'   => Session::class,
            'song'      => Song::class,
            'person'    => Person::class,
            'recording' => Recording::class
        ];

        if (!in_array($input['resource_type'], array_keys($types))) {
            throw new \Exception('The resource type is not valid');
        }

        $id = (int) $input['resource_id'];
        $type = $input['resource_type'];

        $user = auth()->user();
        $favourite = $user->favourites()->where('resource_id', $id)->where('resource_type', $type)->first();

        if (!$favourite) {
            throw new \Exception('Unable to find favourite for specified resource');
        }

        $favourite->delete();

        return $favourite;
    }
}
