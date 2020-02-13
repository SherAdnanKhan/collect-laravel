<?php

namespace App\Http\GraphQL\Queries;

use App\Models\Session;
use App\Models\CreditRole;
use Illuminate\Support\Arr;
use App\Models\CollaboratorInvite;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Nuwave\Lighthouse\Exceptions\AuthorizationException;

class GetCreditRoles
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
        $query = CreditRole::newQuery();

        $types = array_keys(CreditRole::TYPES_WITH_LABELS);

        $ordering = Arr::get($args, 'ordering', false);
        if ($ordering) {
            $query = $query->orderByField(['ordering' => $ordering]);
        }

        $isCheckIn = Arr::get($args, 'checkIn', false);
        if ($isCheckIn) {
            $roleTypes = (new Session())->getContributorRoleTypes();
            return $query->whereIn('ddex_key', CreditRole::CHECKIN_ROLE_KEYS)
                ->whereIn('type', $roleTypes)
                ->get();
        }

        $type = Arr::get($args, 'type', false);
        if ($type && in_array($type, $types)) {
            $query = $query->where('type', $type);
        }

        return $query->get();
    }
}
