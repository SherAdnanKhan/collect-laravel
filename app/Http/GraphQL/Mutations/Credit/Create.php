<?php

namespace App\Http\GraphQL\Mutations\Credit;

use App\Http\GraphQL\Exceptions\ValidationException;
use App\Models\Credit;
use App\Models\Party;
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
        $partyId = (int) array_get($args, 'input.party_id');

        $types = [
            'project'   => Project::class,
            'session'   => Session::class,
            'song'      => Song::class,
            'recording' => Recording::class
        ];

        if (!in_array(array_get($input, 'contribution_type'), array_keys($types))) {
            throw new AuthorizationException('The contribution type is not valid');
        }

        $party = Party::where('id', $partyId)->userViewable()->first();

        if (!$party) {
            throw new AuthorizationException('Unable to find party to save credit for');
        }

        if ($input['contribution_type'] == 'song') {
            $song = Song::find($input['contribution_id']);
            $currentSplit = $song->credits->sum('split');
            if ($currentSplit + $input['split'] > 100) {
                throw new ValidationException('Total Split cannot be over 100.', null, null, null, null, null, [
                    'validation' => [
                        'split' => ['Total split cannot be over 100.']
                    ]
                ]);
            }
        }

        return $party->credits()->firstOrCreate(array_only($input, [
            'contribution_id',
            'contribution_type',
            'credit_role_id',
            'instrument_id',
            'performing',
            'split'
        ]));
    }
}
