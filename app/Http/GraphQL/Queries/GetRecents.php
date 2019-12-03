<?php

namespace App\Http\GraphQL\Queries;

use App\Models\EventLog;
use App\Models\Project;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Exceptions\AuthorizationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class GetRecents
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
        $count = array_get($args, 'count', 5);
        $user = auth()->user();

        $query = $user->eventLogs();

        if (array_key_exists('resourceType', $args) && in_array(array_get($args, 'resourceType'), EventLog::TYPES)) {
            $query = $query->where('event_logs.resource_type', array_get($args, 'resourceType'));
        } else {
            $query = $query->where('event_logs.resource_type', '!=', 'collaborator');
        }

        return $query->where('event_logs.resource_type', '!=', 'comment')
            ->where('event_logs.action', '<>', 'delete')
            ->orderBy('event_logs.updated_at', 'desc')
            ->orderBy('event_logs.created_at', 'desc')
            ->groupBy('event_logs.resource_id', 'event_logs.resource_type')
            ->take($count)
            ->get();
    }
}
