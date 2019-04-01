<?php

namespace App\Http\GraphQL\Mutations;

use App\Http\GraphQL\Exceptions\ValidationException;
use App\Models\Folder;
use App\Models\Project;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Exceptions\AuthorizationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * Handle the deletion of files.
 */
class CreateFolder
{
    /**
     * @param $rootValue
     * @param array $args
     * @param \Nuwave\Lighthouse\Support\Contracts\GraphQLContext|null $context
     * @param \GraphQL\Type\Definition\ResolveInfo $resolveInfo
     * @return array
     * @throws \AuthorizationException
     */
    public function resolve(
        $rootValue,
        array $args,
        GraphQLContext $context = null,
        ResolveInfo $resolveInfo
    ): array
    {
        $project = Project::where('id', $args['input']['projectId'])->userViewable()->first();
        if (!$project) {
            throw new AuthorizationException('Unable to find project to associate session to');
        }

        $user = auth()->user();
        if (!$user->can('create', [Folder::class, $project])) {
            throw new AuthorizationException('User does not have permission to create a folder on this project');
        }

        $parent_folder = false;
        if (isset($args['input']['folderId'])) {
            $parent_folder = Folder::where('project_id', $project->id)->where('id', $args['input']['folderId'])->userViewable()->first();
            if (!$parent_folder) {
                throw new AuthorizationException;
            }
        }

        $name = preg_replace('/([^a-zA-Z0-9\!\-\_\.\*\,\(\)]+)/', '', $args['input']['name']);
        $duplicate_folder_query = Folder::where('project_id', $project->id)->userViewable()->where('name', 'like', $name);
        if ($parent_folder) {
            $duplicate_folder_query->where('folder_id', $parent_folder->id);
        } else {
            $duplicate_folder_query->whereNull('folder_id');
        }

        if ($duplicate_folder_query->count() > 0) {
            throw new ValidationException('Folder with that name already exists.');
        }

        $folder = Folder::create([
            'project_id' => $project->id,
            'folder_id' => ($parent_folder ? $parent_folder->id : null),
            'user_id' => $user->id,
            'name' => $name,
            'depth' => ($parent_folder ? $parent_folder->depth + 1 : 0)
        ]);

        return $folder->toArray();
    }
}
