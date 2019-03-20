<?php

namespace App\Http\GraphQL\Mutations;

use App\Models\User;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Validator;

class ResetPassword
{
    use ResetsPasswords;

    /**
     * @param $rootValue
     * @param array $args
     * @param \Nuwave\Lighthouse\Support\Contracts\GraphQLContext|null $context
     * @param \GraphQL\Type\Definition\ResolveInfo $resolveInfo
     * @return array
     */
    public function resolve($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {
        $input = array_get($args, 'input');

        $response = $this->broker()->reset($input, function ($user, $password) {
            $this->resetPassword($user, $password);
        });

        return [
            'success' => $response == Password::PASSWORD_RESET
        ];
    }

    private function broker()
    {
        return Password::broker();
    }
}
