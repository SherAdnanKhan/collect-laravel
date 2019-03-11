<?php

namespace App\Http\GraphQL\Mutations\UserFavourite;

use App\Models\File;
use App\Models\Folder;
use App\Models\Person;
use App\Models\Project;
use App\Models\Recording;
use App\Models\Session;
use App\Models\Song;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Exceptions\AuthenticationException;
use Nuwave\Lighthouse\Exceptions\AuthorizationException;
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
        $input = $args['input'];

        $types = [
            'file'      => File::class,
            'folder'    => Folder::class,
            'project'   => Project::class,
            'session'   => Session::class,
            'song'      => Song::class,
            'person'    => Person::class,
            'recording' => Recording::class
        ];

        if (!in_array($input['resource_type'], array_keys($types))) {
            throw new AuthorizationException('The resource type is not valid');
        }

        $id = (int) $input['resource_id'];
        $type = $input['resource_type'];

        $model = $types[$type];

        if (!$model::where('id', $id)->userViewable()->first()) {
            throw new AuthorizationException('Unable to find resource to favourite');
        }

        $user = auth()->user();
        return $user->favourites()->firstOrCreate([
            'resource_id'   => $id,
            'resource_type' => $type,
        ]);
    }
}
