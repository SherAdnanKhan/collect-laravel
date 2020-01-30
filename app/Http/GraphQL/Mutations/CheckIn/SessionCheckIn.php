<?php

namespace App\Http\GraphQL\Mutations\CheckIn;

use App\Models\User;
use App\Models\Party;
use App\Models\Session;
use App\Models\CreditRole;
use App\Models\SessionCode;
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

        $success = rescue(function() use ($request, $token){
            $tokenKey = SessionCode::checkinCacheKey($token);
            $sessionId = Cache::get($tokenKey);
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
            $firstName = array_get($profileData, 'first_name');

            $party = Party::where('first_name', $firstName)
                ->whereHas('contacts', function($query) use ($email) {
                    return $query->where('email', $email);
                })
                ->relatedToProject(['project' => $session->project])
                ->first();

            if (is_null($party)) {
                $party = Party::create($profileData);
            }

            $role = CreditRole::where('ddex_key', 'Artist')->firstOrFail();

            $credit = $party->credits()->firstOrCreate([
                'contribution_id'   => $session->id,
                'contribution_type' => 'session',
                'credit_role_id'    => $role->id,
                'instrument_id'     => array_get($profileData, 'instrument_id', null),
                'performing'        => true,
            ]);

            return !is_null($credit);
        }, false);

        if ($success) {
            Cache::forget($token);
        }

        return [
            'success' => $success,
        ];
    }
}
