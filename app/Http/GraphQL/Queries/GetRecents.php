<?php

namespace App\Http\GraphQL\Queries;

use App\Models\Project;
use App\Models\EventLog;
use Illuminate\Support\Facades\DB;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Nuwave\Lighthouse\Exceptions\AuthorizationException;

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

        // TODO:
        // Filter out the event logs so that we're only pulling ones for projects which the
        // user owns or is a collaborator on.

        if (array_key_exists('resourceType', $args) && in_array(array_get($args, 'resourceType'), EventLog::TYPES)) {
            $query = $query->where('event_logs.resource_type', array_get($args, 'resourceType'));
        } else {
            $query = $query->where('event_logs.resource_type', '<>', 'collaborator');
        }

        $eventLogs = $query->where('event_logs.resource_type', '<>', 'comment')
            ->where('event_logs.action', '<>', 'delete')
            ->where(function($query) {
                return $query->whereExists(function($query) {
                    // User is a collaborator on the project where the
                    // event log occurred.
                    return $query->select(DB::raw(1))
                        ->from('collaborators')
                        ->whereRaw('collaborators.user_id = event_logs.user_id')
                        ->whereRaw('collaborators.project_id = event_logs.project_id');
                })->orWhereExists(function($query) {
                    // User is the owner of the project where the
                    // event log occurred.
                    return $query->select(DB::raw(1))
                        ->from('projects')
                        ->whereRaw('projects.id = event_logs.project_id')
                        ->whereRaw('projects.user_id = event_logs.user_id');
                });
            })
            ->orderBy('event_logs.created_at', 'desc')
            ->groupBy('event_logs.resource_id', 'event_logs.resource_type')
            ->take($count)
            ->get();

        return $eventLogs;
    }
}
