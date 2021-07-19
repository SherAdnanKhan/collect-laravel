<?php

namespace App\Http\GraphQL\Queries\PPL;

use App\Models\TmpIntegration;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class GetUrl
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
        $nonce = time();

        try {
            TmpIntegration::UpdateOrCreate(['user_id' => Auth::user()->id], ['key' => $nonce]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            abort(500, 'Could not create integration key');
        }

        return [
            'url' => sprintf(env('PPL_LOGIN_URL'), $nonce)
        ];
    }
}
