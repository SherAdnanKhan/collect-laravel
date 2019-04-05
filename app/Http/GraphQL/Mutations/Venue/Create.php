<?php

namespace App\Http\GraphQL\Mutations\Venue;

use App\Models\Venue;
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
            $input['address'] = $this->buildAddress($input);
            $venue = $user->venues()->firstOrCreate(array_only($input, 'name', 'country', 'address'));
        } catch (\Exception $e) {
            throw new GenericException($e->getMessage());
        }

        return $venue;
    }

    private function buildAddress(array $input)
    {
        $address = [
            isset($input['address_1']) ? $input['address_1'] : null,
            isset($input['address_2']) ? $input['address_2'] : null,
            isset($input['city']) ? $input['city'] : null,
            isset($input['state']) ? $input['state'] : null,
            isset($input['zip']) ? $input['zip'] : null,
        ];

        return join(', ', array_filter($address));
    }
}
