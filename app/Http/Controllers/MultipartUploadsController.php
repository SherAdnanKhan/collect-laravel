<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Folder;
use App\Models\Project;
use App\Scopes\VisibleScope;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class MultipartUploadsController extends Controller
{
    /**
     * Handle the request to create the uploaded files
     * and folders in the system.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        // TODO:
        // Use the unique token to batch folder creations for duplicates,
        // so that we don't keep creating new folders for each file.

        // Once we've worked out what folder we're using to save the files under we
        // Don't need to check for more.

        $meta = $request->get('meta');

        // Get the current user
        $user = auth()->user();

        $project = false;
        $projectId = null;

        $batchId = array_get($meta, 'batchId', false);

        $size = (int)$request->get('size');

        // if we're uploading to a project
        // make sure we have access and create a path
        // to the project folder
        if ($meta['projectId']) {
            $project = Project::where('id', $meta['projectId'])
                ->with('user', 'user.subscriptions')
                ->userViewable()
                ->first();

            if (!$project) {
                abort(403, 'Unauthorized action.');
            }

            $projectId = $project->id;

            if ($project->user->hasStorageSpaceAvailable($size) === false) {
                return response()->json([
                    'upgradeRequired' => true
                ]);
            }

            $path = [
                'uploads',
                'projects',
                $project->getUploadFolderPath()
            ];
        } else {
            // Otherwise we're uploading to the
            // users space
            if ($user->hasStorageSpaceAvailable($size) === false) {
                return response()->json([
                    'upgradeRequired' => true
                ]);
            }

            $path = [
                'uploads',
                'users',
                $user->getUploadFolderPath()
            ];
        }

        // Get the current folder we're uploading to.
        $folder = null;
        if (isset($meta['folderId'])) {
            $query = Folder::withoutGlobalScope(VisibleScope::class)->where('id', $meta['folderId']);

            // If we're uploading to a project we need to filter it out
            // Otherwise we only want folders which have no project
            if ($project) {
                $query = $query->where('project_id', $project->id);
            } else {
                $query = $query->whereNull('project_id');
            }

            // Just need the first one
            $folder = $query->first();

            if (!$folder) {
                abort(403, 'Unauthorized action.');
            }

            // Build the path to this folder
            $folderPath = array_map(function ($item) {
                return $item['name'];
            }, $folder->path);

            $folderPath[] = $folder->name;
            $path = $path + $folderPath;
        }

        // Sleep for between 0 and 1 seconds to try to prevent issues
        // with folder name and filename collisions
        usleep(rand(0, 1000000));

        // Start at the current folder and at it's depth
        $currentFolder = $folder;
        $depth = ($folder ? $folder->depth : 0);

        // Build the directory structure
        $data = $request->get('data');

        if (!empty($data['fullPath']) && empty($data['relativePath'])) {
            $data['relativePath'] = $data['fullPath'];
        }

        if (!isset($data['relativePath'])) {
            $data['relativePath'] = '/' . $meta['name'];
        }

        $pathinfo = pathinfo($data['relativePath']);
        foreach (explode('/', $pathinfo['dirname']) as $name) {
            // If the folder name is empty, skip it.
            if (empty($name) || str_replace('.', '', $name) == '') {
                continue;
            }

            // Increase the folder depth
            $depth = $depth + 1;

            // Find the folder row which matches the one we're in (based on path)
            $originalQuery = Folder::withoutGlobalScope(VisibleScope::class);

            // if we're at a project level then filter down by project
            // otherwise we want folders which aren't project related AND
            // are owned by the user
            if ($project) {
                $originalQuery = $originalQuery->where('project_id', $project->id);
            } else {
                $originalQuery = $originalQuery->whereNull('project_id')->where('user_id', $user->id);
            }

            // If we've got a folder we're uploading into, make sure the folder
            // we've just found is inside that.
            if ($currentFolder) {
                $originalQuery = $originalQuery->where('folder_id', $currentFolder->id);
            }

            list($isAppFolder, $extension) = $this->folderNameIsApplicationFolder($name);

            if ($isAppFolder) {
                $count = 0;
                $batchFolderName = false;

                if ($batchId) {
                    $batchFolderName = Cache::get('folders.' . $batchId, false);

                    Log::debug('Folder for batch ' . $batchId, [$batchFolderName]);
                }

                if (!$batchFolderName) {
                    // If we got a result when querying the folder
                    // set our current folder to that one and
                    // continue going down.
                    $checking = true;
                    $count = 0;
                    list($originalName, $extension) = explode('.', $name);

                    while ($checking) {
                        $searchingName = $name;
                        if ($count > 0) {
                            $searchingName = sprintf('%s(%d).%s', $originalName, $count, $extension);
                        }

                        $query = (clone $originalQuery)->where('name', 'like', $searchingName);
                        if (!$query->exists()) {
                            break;
                        }

                        $count++;
                    }
                } else {
                    $name = $batchFolderName;
                    $currentFolder = (clone $originalQuery)->where('name', 'like', $name)->first();
                    continue;
                }
            } else {
                $originalQuery->where('name', 'like', $name);
                $count = $originalQuery->count();
            }

            if ($count > 0) {
                // If the folder isn't an application folder
                if (!$isAppFolder) {
                    // Push the folder name into the overall path
                    $path[] = $name;

                    // use this folder
                    $currentFolder = $originalQuery->first();
                    continue;
                }

                // If it's an application folder we want to treat it as
                // a new uploaded "extension folder" using the "(n)" pattern
                // like we do with files.
                list($originalName, $extension) = explode('.', $name);
                $name = sprintf('%s(%d).%s', $originalName, $count, $extension);

                // Push the folder name into the overall path
                $path[] = $name;
            }

            // If we've got a current folder make the new folder a child of that
            $folderId = ($currentFolder ? $currentFolder->id : null);

            // by default the root folder is the current one (if we're at the top level)
            $rootFolderId = $folderId;

            // If the parent folder has a root however, we're going to use that.
            if ($currentFolder && $currentFolder->root_folder_id) {
                $rootFolderId = $currentFolder->root_folder_id;
            }

            Log::info(
                sprintf(
                    'Creating folder %s, is application? %s with extenstion %s',
                    $name,
                    $isAppFolder ? 'yes' : 'no',
                    $extension
                )
            );

            if ($isAppFolder) {
                $depth += 1;

                if ($batchId) {
                    Cache::put('folders.' . $batchId, $name, 120);
                    Log::debug('Saving folder for batch ' . $batchId, [$name]);
                }
            }

            // Otherwise, we'll create the folder because we didn't find one
            // inside the path.
            $currentFolder = Folder::create([
                'user_id' => $user->id,
                'project_id' => $projectId,
                'folder_id' => $folderId,
                'root_folder_id' => $rootFolderId,
                'name' => $name,
                'depth' => $depth,
                'hidden' => $isAppFolder,
            ]);

            // If the folder is an application folder we need
            // to create a file alias to it.
            if ($isAppFolder) {
                Log::info('Creating alias for application folder');

                File::create([
                    'user_id' => $user->id,
                    'project_id' => $projectId,
                    'folder_id' => $folderId,
                    'aliased_folder_id' => $currentFolder->id,
                    'path' => implode('/', $path) . '/' . $name,
                    'name' => $name,
                    'type' => $extension,
                    'status' => File::STATUS_COMPLETE,
                    'hidden' => false,
                ]);
            }
        }

        $hidden = ($currentFolder ? $currentFolder->hidden : false);
        $folderId = ($currentFolder ? $currentFolder->id : null);

        // Check the filename for duplicates
        $originalFilename = array_get($pathinfo, 'filename', '');
        $extension = array_get($pathinfo, 'extension', '');

        // We'll calculate the filename, if we have duplicates we will add the
        // count to the filename.
        $filename = $this->calculateFilename($currentFolder, $originalFilename, $extension, $project, $user);
        $fullFilename = $filename . '.' . $extension;

        // Generate the S3 key
        $key = implode('/', $path) . '/' . time() . substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 16) . '/' . $fullFilename;

        // Create the file
        $file = File::create([
            'user_id'    => $user->id,
            'project_id' => $projectId,
            'folder_id'  => $folderId,
            'path'       => $key,
            'name'       => $fullFilename,
            'type'       => $extension,
            'status'     => File::STATUS_PENDING,
            'hidden'     => $hidden,
            'size'       => $size
        ]);

        // uplaod the upload
        $s3 = $this->getS3Client();
        $result = $s3->createMultipartUpload([
            'Bucket' => config('filesystems.disks.s3.bucket'),
            'Key'    => $key,
            'ACL'    => 'private'
        ]);

        return response()->json([
            'id'        => $file->id,
            'projectId' => $projectId,
            'folderId'  => $folderId,
            'name'      => $fullFilename,
            'uploadId'  => array_get($result, 'UploadId'),
            'key'       => $key,
        ]);
    }

    public function prepare(Request $request)
    {
        $s3 = $this->getS3Client();
        $cmd = $s3->getCommand('uploadPart', [
            'Bucket' => config('filesystems.disks.s3.bucket'),
            'UploadId' => $request->get('uploadId'),
            'Key' => $request->get('key'),
            'PartNumber' => $request->get('number'),
        ]);

        $request = $s3->createPresignedRequest($cmd, '+24 hour');

        return response()->json([
            'url' => (string)$request->getUri()
        ]);
    }

    public function list(Request $request)
    {
        $s3 = $this->getS3Client();
        $result = $s3->listParts([
            'Bucket' => config('filesystems.disks.s3.bucket'),
            'UploadId' => $request->get('uploadId'),
            'Key' => $request->get('key')
        ]);

        return response()->json($result->get('Parts'));
    }

    public function abort(Request $request)
    {
        $file_query = File::withoutGlobalScope(VisibleScope::class)
            ->where('id', $request->get('id'))
            ->where('status', File::STATUS_PENDING)
            ->where('project_id', $request->get('projectId'))
            ->userViewable();

        if ($request->has('folderId')) {
            $file_query->where('folder_id', $request->get('folderId'));
        } else {
            $file_query->whereNull('folder_id');
        }

        $file = $file_query->first();
        if (!$file) {
            return response()->json([
                'success' => false
            ]);
        }

        $s3 = $this->getS3Client();
        try {
            $result = $s3->abortMultipartUpload([
                'Bucket'   => config('filesystems.disks.s3.bucket'),
                'Key'      => $file->path,
                'UploadId' => $request->get('uploadId')
            ]);

            $file->forceDelete();
        } catch (\Exception $e) {
            //
        }

        return response()->json([
            'success' => true
        ]);
    }

    public function complete(Request $request)
    {
        $s3 = $this->getS3Client();
        $parts = $request->get('parts');
        if (empty($parts)) {
            $result = $s3->listParts([
                'Bucket' => config('filesystems.disks.s3.bucket'),
                'UploadId' => $request->get('uploadId'),
                'Key' => $request->get('key')
            ]);

            $parts = (array)$result->get('Parts');
        }

        try {
            $result = $s3->completeMultipartUpload([
                'Bucket'          => config('filesystems.disks.s3.bucket'),
                'Key'             => $request->get('key'),
                'UploadId'        => $request->get('uploadId'),
                'MultipartUpload' => [
                    'Parts' => $parts
                ],
            ]);
        } catch (\Exception $e) {
            abort(400);
        }

        return response()->json([
            'url' => (string)$result['Location']
        ]);
    }

    private function getS3Client()
    {
        $config = config('filesystems.disks.s3');
        return new \Aws\S3\S3Client([
            'region'  => $config['region'],
            'version' => 'latest',
            'credentials' => [
                'key'    => $config['key'],
                'secret' => $config['secret'],
            ]
        ]);
    }

    private function calculateFilename($currentFolder, $originalFilename, $extension, $project, $user)
    {
        $existingFileQueryBase = ($project ?
            File::where('project_id', $project->id)->withoutGlobalScope(VisibleScope::class)
            :
            File::whereNull('project_id')->withoutGlobalScope(VisibleScope::class)->where('user_id', $user->id)
        );

        if ($currentFolder) {
            $existingFileQueryBase->where('folder_id', $currentFolder->id);
        }

        $filename = $originalFilename;
        $existingFile = (clone $existingFileQueryBase)
            ->where('name', 'like', $filename . '.' . $extension)
            ->first();

        $count = 1;
        while (true) {
            if (!$existingFile || ($existingFile->status === File::STATUS_PENDING && $existingFile->user_id === $user->id)) {
                $existingFile->forceDelete();
                break;
            }

            $filename  = $originalFilename . ' ('.$count.')';
            $existingFile = (clone $existingFileQueryBase)
                ->where('name', 'like', $filename . '.' . $extension)
                ->first();
            $count++;
        }

        return $filename;
    }

    private function folderNameIsApplicationFolder($folderName)
    {
        $extensions = config('app.folder.extensions', []);

        if (empty($extensions)) {
            return false;
        }

        $pattern = sprintf('/\w*\.(?P<extension>%s)/i', join('|', $extensions));

        $matches = [];
        preg_match($pattern, $folderName, $matches);

        return [
            count($matches) > 0,
            array_get($matches, 'extension', '')
        ];
    }
}
