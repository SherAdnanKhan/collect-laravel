<?php

namespace App\Http\GraphQL\Mutations;

use App\Models\Share;
use Carbon\Carbon;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Exceptions\AuthorizationException;
use Nuwave\Lighthouse\Exceptions\GenericException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class UpdateShareExpiry
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
        $input = array_get($args, 'input')[0];

        $share = Share::where('id', array_get($input, 'uuid'))
            ->userUpdatable()
            ->first();

        if (!$share) {
            throw new AuthorizationException('Unable to find share to update');
        }

        if (isset($input['expiry']) && !$this->isValidExpiry($input['expiry'])) {
            return [
                'success' => false
            ];
        }

        $share->expires_at = $input['expiry'];
        $saved = $share->save();

        if (!$saved) {
            throw new GenericException('Error updating share expiry');
        }

        return [
            'success' => true
        ];
    }

    private function isValidExpiry($expiry)
    {
        return Carbon::parse($expiry)->gt(Carbon::now());
    }
}
