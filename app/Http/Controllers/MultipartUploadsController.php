<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Folder;
use App\Models\Project;
use \Illuminate\Http\Request;

class MultipartUploadsController extends Controller
{
    public function create(Request $request)
    {
        $meta = $request->get('meta');
        $project = Project::where('id', $meta['projectId'])->userViewable()->first();
        if (!$project) {
            abort(403, 'Unauthorized action.');
        }

        $project_owner = $project->user;
        if ($project_owner->hasStorageSpaceAvailable() === false) {
            return response()->json([
                'upgradeRequired' => true
            ]);
        }

        // Get the current folder
        $folder = null;
        $path = [$project->getUploadFolderPath()];
        if (isset($meta['folderId'])) {
            $folder = Folder::where('id', $meta['folderId'])->where('project_id', $project->id)->first();
            if (!$folder) {
                abort(403, 'Unauthorized action.');
            }

            // Build the path to this folder
            $folder_path = array_map(function($item) {
                return $item['name'];
            }, $folder->path);

            $folder_path[] = $folder->name;
            $path = $path + $folder_path;
        }

        // Get the current user
        $user = auth()->user();

        // Sleep for between 0 and 1 seconds to try to prevent issues
        // with folder name and filename collisions
        usleep(rand(0, 1000000));

        $currentFolder = $folder;
        $depth = ($folder ? $folder->depth : 0);

        // Build the directory structure
        $data = $request->get('data');
        $pathinfo = pathinfo($data['fullPath']);
        foreach (explode('/', $pathinfo['dirname']) as $name) {
            if (empty($name)) {
                continue;
            }

            $depth = $depth + 1;

            $name = preg_replace('/([^a-zA-Z0-9\!\-\_\.\*\,\(\)].)/', '', $name);

            $folder = Folder::where('project_id', $project->id)->where('name', $name);
            if ($currentFolder) {
                $folder = $folder->where('folder_id', $currentFolder->id);
            }

            $path[] = $name;

            if ($folder->count() > 0) {
                $currentFolder = $folder->first();
                continue;
            }

            $currentFolder = Folder::create([
                'user_id' => $user->id,
                'project_id' => $project->id,
                'folder_id' => ($currentFolder ? $currentFolder->id : null),
                'name' => $name,
                'depth' => $depth
            ]);
        }

        // Check the filename for duplicates
        $original_filename = preg_replace('/([^a-zA-Z0-9\!\-\_\.\*\,\(\)].)/', '', $pathinfo['filename']);
        $extension = $pathinfo['extension'];
        $existing_file_query_base = File::where('project_id', $project->id);
        if ($currentFolder) {
            $existing_file_query_base->where('folder_id', $currentFolder->id);
        }
        $filename = $original_filename;
        $existing_file = (clone $existing_file_query_base)->where('name', 'like', $filename . '.' . $extension)->first();
        $count = 1;
        while ($existing_file) {
            $filename  = $original_filename . ' ('.$count.')';
            $existing_file = (clone $existing_file_query_base)->where('name', 'like', $filename . '.' . $extension)->first();
            $count++;
        }

        $key = implode('/', $path) . '/' . $filename . '.' . $extension;

        $file = File::create([
            'user_id' => $user->id,
            'project_id' => $project->id,
            'folder_id' => ($currentFolder ? $currentFolder->id : null),
            'path' => $key,
            'name' => $filename . '.' . $extension,
            'type' => $extension,
            'status' => File::STATUS_PENDING
        ]);

        $s3 = $this->getS3Client();
        $result = $s3->createMultipartUpload([
            'Bucket' => config('filesystems.disks.s3.bucket'),
            'Key'    => $key,
            'ACL'    => 'private'
        ]);

        $uploadId = $result['UploadId'];

        return response()->json([
            'id'        => $file->id,
            'projectId' => $project->id,
            'folderId'  => ($currentFolder ? $currentFolder->id : null),
            'name'      => $filename . '.' . $extension,
            'uploadId'  => $uploadId,
            'key'       => $key,
        ]);
    }

    public function prepare(Request $request)
    {
        $s3 = $this->getS3Client();
        $cmd = $s3->getCommand('uploadPart', [
            'Bucket' => config('filesystems.disks.s3.bucket'),
            'UploadId' => $request->get('uploadId'),
            'Key'    => $request->get('key'),
            'PartNumber'    => $request->get('number'),
        ]);

        $request = $s3->createPresignedRequest($cmd, '+5 minutes');

        return response()->json([
            'url' => (string)$request->getUri()
        ]);
    }

    public function list(Request $request)
    {
        $s3 = $this->getS3Client();
        $result = $s3->listParts([
            'Bucket'   => config('filesystems.disks.s3.bucket'),
            'UploadId' => $request->get('uploadId'),
            'Key' => $request->get('key')
        ]);

        return response()->json($result->get('Parts'));
    }

    public function abort(Request $request)
    {
        $file_query = File::where('id', $request->get('id'))->where('status', File::STATUS_PENDING)->where('project_id', $request->get('projectId'))->userViewable();
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
                'Bucket'   => config('filesystems.disks.s3.bucket'),
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
        return new \Aws\S3\S3Client([
            'region'  => config('filesystems.disks.s3.region'),
            'version' => 'latest',
            'credentials' => [
                'key'    => config('filesystems.disks.s3.key'),
                'secret' => config('filesystems.disks.s3.secret'),
            ]
        ]);
    }
}