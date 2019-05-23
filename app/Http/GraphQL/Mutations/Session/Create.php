<?php

namespace App\Http\GraphQL\Mutations\Session;

use App\Http\GraphQL\Exceptions\ValidationException;
use App\Models\Project;
use App\Models\Session;
use Carbon\Carbon;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Exceptions\AuthenticationException;
use Nuwave\Lighthouse\Exceptions\AuthorizationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class Create
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
        $user = auth()->user();
        $input = array_get($args, 'input');
        $projectId = (int) array_get($args, 'input.project_id');

        $project = Project::find($projectId);

        if (!$project) {
            throw new AuthorizationException('Unable to find project to associate session to');
        }

        if (!$user->can('create', [Session::class, $project])) {
            throw new AuthorizationException('User does not have permission to create a session on this project');
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

        if ($started_at > $ended_at) {
            throw new ValidationException('Session Started At must be before Ended At.', null, null, null, null, null, [
                'validation' => [
                    'ended_at' => ['Session Ended At must be after Started At.']
                ]
            ]);
        }

        return $project->sessions()->create($input);
    }
}
