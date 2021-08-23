<?php

namespace App\Http\GraphQL\Mutations\PartyAffiliation;

use App\Http\GraphQL\Mutations\Login;
use App\Models\PartyAffiliation;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Log;
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

        $affiliation = PartyAffiliation::where([
            'party_id' => (int)array_get($input, 'party_id'),
            'user_affiliation_id' => (int)array_get($input, 'user_affiliation_id')
        ])->userDeletable();

        if (!$affiliation->exists()) {
            throw new AuthorizationException('Unable to find party affiliation to delete');
        }

        try {
            $affiliation->delete();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return ['success' => false];
        }

        return ['success' => true];
    }
}
