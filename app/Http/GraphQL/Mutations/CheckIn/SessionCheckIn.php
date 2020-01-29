<?php

namespace App\Http\GraphQL\Mutations;

use App\Models\User;
use App\Models\Session;
use Illuminate\Support\Facades\Cache;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Nuwave\Lighthouse\Exceptions\AuthorizationException;
use Nuwave\Lighthouse\Exceptions\AuthenticationException;

class SessionCheckIn
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
        $request = request();

        $token = $request->get('accessToken');

        if (!$token) {
            throw new AuthorizationException('Missing access token');
        }

        $sessionId = Cache::get($token);
        $session = Session::find($sessionId);

        $profileData = $request->only([
            'title',
            'first_name',
            'middle_name',
            'last_name',
            'email',
            'suffix',
            'birth_date',
            'instrument_id',
            'instrument_user_defined_value',
        ]);

        $email = array_get($profileData, 'email');

        // TODO:
        // Given the session and some profile information we'll create a party and a credit against
        // the session, if the party already exists against this session we'll use that. (matching on email and name?)

        // TODO: Return a payload valid with this GraphQL type.
        // type SessionCheckinPayload {
        //     success: Boolean!
        // }

        Cache::forget($token);

        return [];
    }
}
