<?php

namespace App\Http\GraphQL\Queries\Credit;

use App\Models\Credit;
use App\Models\Project;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Exceptions\AuthorizationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class ByRecording
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
        $recordingId = (int) array_get($args, 'recordingId');

        $recording = Recording::where('id', $recordingId)
            ->with('credits')
            ->userViewable()
            ->first();

        if (!$recording) {
            throw new AuthorizationException('The user does not have access to view this recordings credits');
        }

        return $recording->sessions()->take($args['count'])
            ->orderBy('created_at', 'DESC')
            ->paginate();
    }
}
