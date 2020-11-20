<?php

namespace App\Http\GraphQL\Mutations;

use App\Models\Share;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Exceptions\AuthorizationException;
use Nuwave\Lighthouse\Exceptions\GenericException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class ExpireShare
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

        $share = Share::where('id', array_get($input, 'uuid'))
            ->userUpdatable()
            ->first();

        if (!$share) {
            throw new AuthorizationException('Unable to find share to update');
        }

        $share->status = Share::STATUS_CANCELLED;
        $saved = $share->save();

        if (!$saved) {
            throw new GenericException('Error expiring share');
        }

        return [
            'success' => true
        ];
    }
}
