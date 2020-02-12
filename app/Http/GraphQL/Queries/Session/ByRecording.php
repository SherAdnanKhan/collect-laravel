<?php

namespace App\Http\GraphQL\Queries\Session;

use App\Models\Session;
use App\Models\Recording;
use Illuminate\Support\Arr;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Nuwave\Lighthouse\Exceptions\AuthorizationException;

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

        $query = $recording->sessions();

        if (Arr::has($args, 'count')) {
            $query = $query->take(Arr::get($args, 'count'));
        }

        return $query->orderBy('created_at', 'DESC')
            ->paginate();
    }
}
