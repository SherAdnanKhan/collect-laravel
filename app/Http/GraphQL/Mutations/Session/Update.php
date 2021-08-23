<?php

namespace App\Http\GraphQL\Mutations\Session;

use App\Http\GraphQL\Exceptions\ValidationException;
use App\Models\Session;
use Carbon\Carbon;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Exceptions\AuthorizationException;
use Nuwave\Lighthouse\Exceptions\GenericException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

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
        $input = $args['input'];
        $id = (int)array_get($args, 'input.id');

        $session = Session::where('id', $id)->userUpdatable()->first();

        if (!$session) {
            throw new AuthorizationException('Unable to find session to update');
        }

        $started_at = Carbon::parse($input['started_at']);
        $ended_at = Carbon::parse($input['ended_at']);
        if ($started_at->isFuture()) {
            throw new ValidationException('Session Started At must be in the past.', null, null, null, null, null, [
                'validation' => [
                    'started_at' => ['Session Started At must be in the past.']
                ]
            ]);
        }

        if ($input['ended_at'] && $started_at > $ended_at) {
            throw new ValidationException('Session Started At must be before Ended At.', null, null, null, null, null, [
                'validation' => [
                    'ended_at' => ['Session Ended At must be after Started At.']
                ]
            ]);
        }

        $saved = $session->fill($input)->save();

        if (!empty($input['recording_id'])) {
            $session->recordings()->sync($input['recording_id']);
        }

        if (!$saved) {
            throw new GenericException('Error saving session');
        }

        return $session;
    }
}
