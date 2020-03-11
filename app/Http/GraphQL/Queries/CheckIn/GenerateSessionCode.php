<?php

namespace App\Http\GraphQL\Queries\CheckIn;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Session;
use App\Models\SessionCode;
use Illuminate\Support\Arr;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Nuwave\Lighthouse\Exceptions\AuthorizationException;

class GenerateSessionCode
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
        $sessionId = Arr::get($args, 'input.session_id', false);

        $session = Session::where('id', $sessionId)->userViewable()->first();

        if (!$session) {
            throw new AuthorizationException('The user does not have access to this session.');
        }

        // Make sure we don't have the code already.
        $code = SessionCode::generateCode();
        while (SessionCode::where('code', $code)->exists()) {
            $code = SessionCode::generateCode();
        }

        $sessionCode = $session->sessionCodes()->create([
            'code'       => $code,
            'expires_at' => Carbon::now()->addDays(7),
        ]);

        return [
            'sessionCode' => $sessionCode,
        ];
    }
}
