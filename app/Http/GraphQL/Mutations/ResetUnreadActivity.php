<?php

namespace App\Http\GraphQL\Mutations;

use App\Models\EventLog;
use Carbon\Carbon;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Nuwave\Lighthouse\Exceptions\AuthenticationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class ResetUnreadActivity
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
        $projectId = array_get($args, 'input.project_id');
        $user = auth()->user();

        // Reset the counter
        $counterKey = sprintf(EventLog::UNREAD_CACHE_KEY_FORMAT, $projectId, $user->id);
        Cache::forever($counterKey, 0);

        // Set the users last read at key
        $dateTime = Carbon::now()->toDateTimeString();
        $lastReadKey = sprintf(EventLog::LAST_READ_CACHE_KEY_FORMAT, $projectId, $user->id);
        Cache::forever($lastReadKey, $dateTime);

        return [
            'project_id' => $projectId,
            'user_id'    => $user->id,
            'count'      => 0,
            'last_read'  => $dateTime,
        ];
    }
}
