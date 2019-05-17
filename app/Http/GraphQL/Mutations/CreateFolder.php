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
        $user = auth()->user();

        $project = false;
        if ($args['input']['projectId']) {
            $project = Project::where('id', $args['input']['projectId'])->userViewable()->first();
            if (!$project) {
                throw new AuthorizationException('Unable to find project to associate session to');
            }

            if (!$user->can('create', [Folder::class, $project])) {
                throw new AuthorizationException('User does not have permission to create a folder on this project');
            }
        }

        $parent_folder = false;
        if (isset($args['input']['folderId'])) {
            $query = Folder::where('id', $args['input']['folderId']);

            if ($project) {
                $query = $query->where('project_id', $project->id)->userViewable();
            } else {
                $query = $query->whereNull('project_id')->where('user_id', $user->id);
            }

            $parent_folder = $query->first();
            if (!$parent_folder) {
                throw new AuthorizationException;
            }
        }

        $name = $args['input']['name'];
        if (empty($name) || str_replace('.', '', $name) == '') {
            throw new ValidationException('Folder with that name already exists.');
        }
        # $name = preg_replace('/([^a-zA-Z0-9\!\-\_\.\*\,\(\)]+)/', '', $args['input']['name']);
        $duplicate_folder_query = Folder::where('name', 'like', $name);

        if ($project) {
            $duplicate_folder_query = $duplicate_folder_query->where('project_id', $project->id)->userViewable();
        } else {
            $duplicate_folder_query = $duplicate_folder_query->whereNull('project_id')->where('user_id', $user->id);
        }

        if ($parent_folder) {
            $duplicate_folder_query->where('folder_id', $parent_folder->id);
        } else {
            $duplicate_folder_query->whereNull('folder_id');
        }

        if ($duplicate_folder_query->count() > 0) {
            throw new ValidationException('Folder with that name already exists.');
        }

        $folder = Folder::create([
            'project_id' => ($project ? $project->id : null),
            'folder_id' => ($parent_folder ? $parent_folder->id : null),
            'user_id' => $user->id,
            'name' => $name,
            'depth' => ($parent_folder ? $parent_folder->depth + 1 : 0)
        ]);

        return $folder->toArray();
    }
}
