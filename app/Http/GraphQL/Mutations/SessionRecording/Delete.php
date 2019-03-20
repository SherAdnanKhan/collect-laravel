<?php

namespace App\Http\GraphQL\Mutations\SessionRecording;

use App\Models\Recording;
use App\Models\Session;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Exceptions\AuthorizationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class Delete
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

        $session = Session::where('id', array_get($input, 'session_id'))->userUpdatable()->first();

        if (!$session) {
            throw new AuthorizationException('Unable to find session to remove recording from.');
        }

        $recording = Recording::where('id', array_get($input, 'recording_id'))->userViewable()->first();

        if (!$recording) {
            throw new AuthorizationException('Unable to find recording to remove from session.');
        }

        $session->recordings()->detach($recording->id);

        return [
            'session_id'   => $session->id,
            'recording_id' => $recording->id,
        ];
    }
}
