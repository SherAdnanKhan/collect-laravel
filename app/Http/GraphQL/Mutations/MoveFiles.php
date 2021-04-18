<?php

namespace App\Http\GraphQL\Mutations;

use App\Http\GraphQL\Exceptions\ValidationException;
use App\Models\File;
use App\Models\Folder;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Log;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * Handle the deletion of files.
 */
class MoveFiles
{
    /**
     * @param $rootValue
     * @param array $args
     * @param \Nuwave\Lighthouse\Support\Contracts\GraphQLContext|null $context
     * @param \GraphQL\Type\Definition\ResolveInfo $resolveInfo
     * @return array
     * @throws \Exception
     */
    public function resolve(
        $rootValue,
        array $args,
        GraphQLContext $context = null,
        ResolveInfo $resolveInfo
    ): array
    {
        $projectId = $args['input']['project_id'];
        $newFolderId = $args['input']['folder_id'];

        $newFolder = null;
        if ($newFolderId) {
            $newFolder = Folder::where('id', $newFolderId)->where('project_id', $projectId)->userDeletable()->first();
            if (!$newFolder) {
                throw new ValidationException('Cannot move into this folder');
            }

            $newFolderPath = array_map(function($path) {
                return $path['id'];
            }, $newFolder->path);
            $newFolderPath[] = $newFolderId;
        }

        $filesToMove = [];
        $foldersToMove = [];

        $success = false;
        foreach ($args['input']['files'] as $file) {
            if ($file['type'] === 'folder') {
                $folder = Folder::where('id', $file['id'])->where('project_id', $projectId)->with('folders:id,depth')->userDeletable()->first();
                if (!$folder || $folder->readonly) {
                    throw new ValidationException(sprintf('Cannot move folder %s', $folder->name));
                }

                /**
                 * Check whether we're actually moving this folder anywhere
                 */
                if ((is_null($newFolderId) && is_null($folder->folder_id)) || ($newFolder && $folder->folder_id === $newFolder->id)) {
                    $filesToMove = [];
                    $foldersToMove = [];
                    break;
                }

                if ($newFolder && in_array($folder->id, $newFolderPath) === true) {
                    throw new ValidationException('You cannot move folders into themselves');
                }

                $foldersToMove[] = $folder;
                continue;
            }

            $file = File::where('id', $file['id'])->where('project_id', $projectId)->userDeletable()->first();
            if (!$file || $file->status !== File::STATUS_COMPLETE) {
                throw new ValidationException(sprintf('Cannot move file %s', $file->name));
            }

            /**
             * Check whether we're actually moving this file anywhere
             */
            if ((is_null($newFolderId) && is_null($file->folder_id)) || ($newFolder && $file->folder_id === $newFolder->id)) {
                $filesToMove = [];
                $foldersToMove = [];
                break;
            }

            $filesToMove[] = $file;

            if ($file->isAlias()) {
                $foldersToMove[] = $file->aliasFolder;
            }
        }

        foreach ($foldersToMove as $folder) {
            $folder->folder_id = ($newFolder ? $newFolder->id : null);
            $folder->root_folder_id = null;

            if ($newFolder) {
                if ($newFolder->root_folder_id) {
                    $folder->root_folder_id = $newFolder->root_folder_id;
                } else {
                    $folder->root_folder_id = $newFolder->id;
                }
            }

            $folder->save();

            $this->updateFolderDepths($newFolder, $folder);
        }

        foreach ($filesToMove as $file) {
            $file->folder_id = ($newFolder ? $newFolder->id : null);
            $file->save();
        }

        return [
            'success' => true
        ];
    }

    /**
     * Recursively go through folders and update their
     * depth based on the parent folder
     *
     * @param Folder|null $parentFolder
     * @param Folder $folder
     */
    private function updateFolderDepths(?Folder $parentFolder, Folder $folder): void
    {
        $folder->depth = ($parentFolder ? $parentFolder->depth + 1 : 0);
        $folder->save();

        $folder->loadMissing('folders:id,depth');

        foreach ($folder->folders as $childFolder) {
            $this->updateFolderDepths($folder, $childFolder);
        }
    }
}
