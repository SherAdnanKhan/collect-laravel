<?php

namespace App\Http\GraphQL\Mutations\User;

use App\Jobs\SMS\SendTwoFactorSMS;
use App\Models\User;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Cache;
use Nuwave\Lighthouse\Exceptions\AuthenticationException;
use Nuwave\Lighthouse\Exceptions\AuthorizationException;
use Nuwave\Lighthouse\Exceptions\GenericException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Propaganistas\LaravelPhone\PhoneNumber;

class Update
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
        $user = auth()->user();

        if (array_key_exists('password', $input) || array_key_exists('phone', $input)) {
            if (!array_key_exists('current_password', $input)) {
                throw new AuthorizationException('Current password must be provided if updating the users password or phone');
            }

            $authed = auth()->attempt([
                'email'    => $user->email,
                'password' => array_get($input, 'current_password'),
            ]);

            if (!$authed) {
                throw new AuthenticationException('User is not authenticated when attempting to update their password or phone');
            }
        }

        try {
            $twoFactorSent = false;
            $twoFactorToken = null;
            $twoFactorExpiry = null;

            // if the user sets a phone
            if (array_key_exists('phone', $input)) {
                // Make sure it's formatted
                $phone = PhoneNumber::make(array_get($input, 'phone'))->formatE164();
                $input['phone'] = $phone;

                $userPhoneChanged = ($phone != $user->phone && (bool) $user->two_factor_enabled);
                $twoFactorChanged = (bool) array_get($input, 'two_factor_enabled', 0) != (bool) $user->two_factor_enabled;

                // If the user has changed their phone, or their 2fa setting has changed.
                if ($userPhoneChanged || $twoFactorChanged)  {
                    $twoFactor = resolve('App\Util\TwoFactorAuthentication');
                    $twoFactorToken = $twoFactor->setMeta([
                        'user_update' => [
                            'phone'              => $phone,
                            'two_factor_enabled' => array_get($input, 'two_factor_enabled', 0),
                        ],
                    ])->setPhone($phone)->setUser($user)->send();

                    $twoFactorExpiry = $twoFactor->getExpiry();
                    $twoFactorSent = true;

                    unset($input['phone']);
                    unset($input['two_factor_enabled']);
                }
            }

            $data = array_except($input, ['password_confirmation', 'current_password']);
            $user->fill($data)->save();
        } catch (\Exception $e) {
            throw new GenericException($e->getMessage());
        }

        // If we need two factor verification, pass the token back here.
        if ($twoFactorSent && !is_null($twoFactorToken)) {
            $user->two_factor = [
                'access_token' => $twoFactorToken,
                'expires_in'   => $twoFactorExpiry,
                'token_type'   => '2fa',
                'two_factor'   => true,
            ];
            return $user;
        }

        return $user;
    }
}
