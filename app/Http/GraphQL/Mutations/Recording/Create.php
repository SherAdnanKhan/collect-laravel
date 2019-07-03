<?php

namespace App\Http\GraphQL\Mutations\Recording;

use App\Models\Folder;
use App\Models\Project;
use App\Models\Recording;
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
        $projectId = (int) array_get($args, 'input.project_id');

        $project = Project::find($projectId);

        if (!$project) {
            throw new AuthorizationException('Unable to find project to associate recording to');
        }

        if (!$user->can('create', [Recording::class, $project])) {
            throw new AuthorizationException('User does not have permission to create a recording on this project');
        }

        $recording = $project->recordings()->create(array_get($args, 'input'));

        $folder = Folder::create([
            'project_id' => $recording->project_id,
            'user_id'    => $user->id,
            'name'       => sprintf('Recording: %s', $recording->name),
            'readonly'   => true
        ]);

        $recording->folder_id = $folder->id;
        $recording->save();

        return $recording;
    }
}
