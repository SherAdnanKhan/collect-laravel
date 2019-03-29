<?php

namespace App\Http\GraphQL\Queries;

use App\Models\EventLog;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Cache;
use Nuwave\Lighthouse\Exceptions\AuthorizationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class GetUnreadActivity
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
        $projectId = array_get($args, 'projectId');
        $user = auth()->user();

        // Reset the counter
        $counterKey = sprintf(EventLog::UNREAD_CACHE_KEY_FORMAT, $projectId, $user->id);

        // If the user doesn't have a last read in this context, we'll
        // set it to the timestamp they were created.
        $lastReadKey = sprintf(EventLog::LAST_READ_CACHE_KEY_FORMAT, $projectId, $user->id);
        if (!Cache::has($lastReadKey)) {
            Cache::forever($lastReadKey, $user->created_at);
        }

        return [
            'project_id' => $projectId,
            'user_id'    => $user->id,
            'count'      => Cache::get($counterKey, 0),
            'last_read'  => Cache::get($lastReadKey, $user->created_at),
        ];
    }
}
