<?php

namespace App\Http\GraphQL\Mutations\PartyContact;

use App\Models\Party;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Auth\Access\AuthorizationException;
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
        $partyId = (int) array_get($args, 'input.party_id');

        $party = Party::where('id', $partyId)->userUpdatable()->first();

        if (!$party) {
            throw new AuthorizationException('Unable to find party to add contact to');
        }

        try {
            $contact = $party->contacts()->create($input);
        } catch (\Exception $e) {
            throw new GenericException($e->getMessage());
        }

        return $contact;
    }
}
