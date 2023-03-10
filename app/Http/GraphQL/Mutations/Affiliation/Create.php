<?php

namespace App\Http\GraphQL\Mutations\Affiliation;

use GraphQL\Type\Definition\ResolveInfo;
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

        try {
            $affiliation = auth()->user()->affiliations()->userViewable()->updateOrCreate(['affiliation_id' => array_get($input, 'affiliation_id')], $input);
        } catch (\Exception $e) {
            throw new GenericException($e->getMessage());
        }

        return $affiliation;
    }
}
