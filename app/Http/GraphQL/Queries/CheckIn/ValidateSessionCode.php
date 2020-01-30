<?php

namespace App\Http\GraphQL\Queries\CheckIn;

use Carbon\Carbon;
use App\Models\User;
use App\Models\SessionCode;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Nuwave\Lighthouse\Exceptions\AuthenticationException;

class ValidateSessionCode
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
        list($valid, $accessToken) = rescue(function() use ($args) {
            $code = array_get($args, 'sessionCode', false);

            $sessionCode = SessionCode::where('code', $code)
                ->notExpired()
                ->firstOrFail();

            Log::debug('sessionCode', [$code, $sessionCode]);

            $token = Str::random(32);

            Log::debug('Generating token for code', [$token, $sessionCode]);

            $tokenKey = SessionCode::checkinCacheKey($token);

            Cache::put($tokenKey, $sessionCode->session_id, now()->addMinutes(30));

            return [true, $token];
        }, [false, null]);

        return [
            'valid' => $valid,
            'accessToken' => $accessToken,
        ];
    }
}
