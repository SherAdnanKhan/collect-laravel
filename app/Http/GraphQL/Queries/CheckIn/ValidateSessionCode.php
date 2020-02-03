<?php

namespace App\Http\GraphQL\Queries\CheckIn;

use Carbon\Carbon;
use App\Models\User;
use App\Models\SessionCode;
use Illuminate\Support\Arr;
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
        list($valid, $accessToken, $session) = rescue(function() use ($args) {
            $code = Arr::get($args, 'sessionCode', false);

            $sessionCode = SessionCode::where('code', $code)
                ->notExpired()
                ->firstOrFail();

            $token = Str::random(32);

            $tokenKey = SessionCode::checkinCacheKey($token);

            Cache::put($tokenKey, $sessionCode->session_id, now()->addMinutes(120));

            return [
                true,
                $token,
                $sessionCode->session
            ];
        }, [false, null]);

        return [
            'valid' => $valid,
            'accessToken' => $accessToken,
            'sessionName' => $session->name
        ];
    }
}
