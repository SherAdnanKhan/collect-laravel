<?php

namespace App\Http\GraphQL\Mutations\Credit;

use App\Models\Credit;
use App\Models\Person;
use App\Models\Project;
use App\Models\Recording;
use App\Models\Session;
use App\Models\Song;
use App\Models\User;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Exceptions\AuthorizationException;
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
        $user = auth()->user();
        $input = array_get($args, 'input');
        $personId = (int) array_get($args, 'input.person_id');

        $types = [
            'project'   => Project::class,
            'session'   => Session::class,
            'song'      => Song::class,
            'recording' => Recording::class
        ];

        if (!in_array(array_get($input, 'contribution_type'), array_keys($types))) {
            throw new AuthorizationException('The contribution type is not valid');
        }

        $person = Person::where('id', $personId)->userViewable()->first();

        if (!$person) {
            throw new AuthorizationException('Unable to find person to save credit for');
        }

        return $person->credits()->firstOrCreate(array_only($input, [
            'contribution_id',
            'contribution_type',
            'role',
            'performing',
        ]));
    }
}
