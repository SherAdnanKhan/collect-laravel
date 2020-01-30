<?php

namespace App\Http\GraphQL\Mutations\CheckIn;

use App\Models\User;
use App\Models\Party;
use App\Models\Session;
use App\Models\CreditRole;
use App\Models\SessionCode;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
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
        $input = Arr::get($args, 'input', []);
        $token = Arr::get($input, 'access_token', false);

        if (!$token) {
            throw new AuthorizationException('Missing access token');
        }

        $tokenKey = SessionCode::checkinCacheKey($token);

        if (!Cache::has($tokenKey)) {
            throw new AuthorizationException('Invalid access token');
        }

        $success = rescue(function() use ($input, $tokenKey) {
            $sessionId = Cache::get($tokenKey);
            $session = Session::find($sessionId);

            $profileData = Arr::only($input, [
                'title',
                'first_name',
                'middle_name',
                'last_name',
                'email',
                'suffix',
                'isni',
                'birth_date',
                'instrument_id',
                'instrument_user_defined_value',
            ]);

            Log::debug('profileData', ['input' => $input]);

            $email = Arr::get($profileData, 'email');
            $firstName = Arr::get($profileData, 'first_name');
            $isni = Arr::get($profileData, 'isni');

            $party = Party::where(function($query) use ($firstName, $email) {
                return $query->where('first_name', $firstName)
                    ->whereHas('contacts', function($query) use ($email) {
                        return $query->where('value', $email)
                            ->where('type', 'email');
                    });
            })->orWhere('isni', $isni)
                ->relatedToProject(['project' => $session->project])
                ->first();

            $dataToInsert = Arr::except($profileData, ['email']);

            if (is_null($party)) {
                $party = Party::create($dataToInsert);
            } else {
                $party->fill($dataToInsert);
                $party->save();
            }

            $party->contacts()->firstOrCreate([
                'type' => 'email',
                'value' => Arr::get($profileData, 'email'),
            ]);

            $role = CreditRole::where('ddex_key', 'Artist')->firstOrFail();

            $credit = $party->credits()->firstOrCreate([
                'contribution_id'   => $session->id,
                'contribution_type' => 'session',
                'credit_role_id'    => $role->id,
                'instrument_id'     => Arr::get($profileData, 'instrument_id', null),
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
