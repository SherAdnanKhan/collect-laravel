<?php

namespace App\Http\GraphQL\Mutations;

use App\Http\GraphQL\Exceptions\ValidationException;
use App\Models\Folder;
use App\Models\Project;
use App\Models\Recording;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Exceptions\AuthorizationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * Handle the deletion of files.
 */
class RenameFolder
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
                throw new AuthorizationException('Unable to find project');
            }
        }

        $query = Folder::where('id', $args['input']['folderId']);
        if ($project) {
            $query->where('project_id', $project->id);
        } else {
            $query->whereNull('project_id')->where('user_id', $user->id);
        }

        $folder = $query->first();
        if (!$folder) {
            throw new AuthorizationException;
        }

        if (Recording::where([
            'folder_id' => $folder->id
        ])->count() > 0) {
            throw new ValidationException('Cannot rename Recording Folder.');
        }

        if ($project && !$user->can('update', [Folder::class, $project, $folder])) {
            throw new AuthorizationException('User does not have permission to update this folder');
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

        $duplicate_folder_query->where('folder_id', $folder->folder_id)->where('id', '!=', $folder->id);

        if ($duplicate_folder_query->count() > 0) {
            throw new ValidationException('Folder with that name already exists.');
        }

        $folder->name = $name;
        $folder->save();

        return $folder->toArray();
    }
}
