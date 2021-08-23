<?php

namespace App\Http\GraphQL\Mutations\Party;

use App\Models\Party;
use App\Models\User;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\DB;
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
        $is_my = !empty($input['is_my']);

        DB::beginTransaction();

        try {
            if ($is_my) {
                $party = Party::query()->updateOrCreate(['is_my' => 1, 'user_id' => auth()->id()], $input);
            } else {
                $party = auth()->user()->parties()->create($input);
            }

            if ($is_my) {
                if (!empty($input['user_affiliation_ids'])) {
                    $party->affiliations()->sync($input['user_affiliation_ids']);
                } else {
                    $party->affiliations()->sync([]);
                }
            } else {
                if (!empty($input['user_affiliation_ids'])) {
                    $party->affiliations()->attach($input['user_affiliation_ids']);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new GenericException($e->getMessage());
        }

        return $party;
    }
}
