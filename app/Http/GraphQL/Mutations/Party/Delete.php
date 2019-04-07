<?php

namespace App\Http\GraphQL\Mutations\Party;

use App\Models\Credit;
use App\Models\Party;
use App\Models\Recording;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Exceptions\AuthorizationException;
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
        $input = array_get($args, 'input');

        $party = Party::where('id', (int) array_get($input, 'id'))
            ->userDeletable()
            ->first();

        if (!$party) {
            throw new AuthorizationException('Unable to find party to delete');
        }

        $recordings_count = Recording::where('party_id', $party->id)->count();
        if ($recordings_count > 0) {
            throw new AuthorizationException('Unable to delete party');
        }

        $credits_count = Credit::where('party_id', $party->id)->count();
        if ($recordings_count > 0) {
            throw new AuthorizationException('Unable to delete party');
        }

        $party->delete();

        return $party;
    }
}
