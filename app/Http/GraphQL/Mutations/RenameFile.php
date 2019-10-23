<?php

namespace App\Http\GraphQL\Mutations;

use App\Http\GraphQL\Exceptions\ValidationException;
use App\Models\File;
use App\Models\Folder;
use App\Models\Project;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Exceptions\AuthorizationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * Handle the deletion of files.
 */
class RenameFile
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

            if (!$user->can('update', [File::class, $project])) {
                throw new AuthorizationException('User does not have permission to rename a file on this project');
            }
        }

        $query = File::where('id', $args['input']['fileId']);

        if ($project) {
            $query = $query->where('project_id', $project->id)->userViewable();
        } else {
            $query = $query->whereNull('project_id')->where('user_id', $user->id);
        }

        if ($args['input']['folderId']) {
            $query = $query->where('folder_id', $input['input']['folderId']);
        }

        $file = $query->first();
        if (!$file) {
            throw new AuthorizationException;
        }

        $name = $args['input']['name'];
        if (empty($name) || str_replace('.', '', $name) == '') {
            throw new ValidationException('File with that name already exists.');
        }
        # $name = preg_replace('/([^a-zA-Z0-9\!\-\_\.\*\,\(\)]+)/', '', $args['input']['name']);
        $duplicate_file_query = File::where('name', 'like', $name);

        if ($project) {
            $duplicate_file_query = $duplicate_file_query->where('project_id', $project->id)->userViewable();
        } else {
            $duplicate_file_query = $duplicate_file_query->whereNull('project_id')->where('user_id', $user->id);
        }

        if ($args['input']['folderId']) {
            $duplicate_file_query = $duplicate_file_query->where('folder_id', $input['input']['folderId']);
        }

        $duplicate_file_query->where('id', '!=', $file->id);

        if ($duplicate_file_query->count() > 0) {
            throw new ValidationException('File with that name already exists.');
        }

        $file->name = $name;
        $file->save();

        return $file->toArray();
    }
}
