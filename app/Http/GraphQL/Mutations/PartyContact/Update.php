<?php

namespace App\Http\GraphQL\Mutations\PartyContact;

use App\Models\PartyContact;
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

        $contact = PartyContact::where('id', (int) array_get($input, 'id'))
            ->userUpdatable()
            ->first();

        if (!$contact) {
            throw new AuthorizationException('Unable to find party contact to update');
        }

        $saved = $contact->fill($input)->save();

        if (!$saved) {
            throw new GenericException('Error saving contact');
        }

        return $contact;
    }
}
