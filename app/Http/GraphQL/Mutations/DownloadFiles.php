<?php

namespace App\Http\GraphQL\Mutations;

use App\Models\File;
use App\Models\Folder;
use App\Scopes\VisibleScope;
use App\Jobs\CreateDownloadZip;
use Illuminate\Support\Facades\Log;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Nuwave\Lighthouse\Exceptions\AuthenticationException;

class DownloadFiles
{
    private $filesToDownload = [];

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
        $this->getFilesToDownload($user, $args['files']);

        if (!isset($this->filesToDownload[0])) {
            return [
                'success' => false
            ];
        }

        $userId = $user->id;

        if (!isset($this->filesToDownload[1]) && $this->filesToDownload[0]->depth === 0) {
            return $this->getFileURL($this->filesToDownload[0]);
        }

        $zipName = $this->getZipName($user, $args['files']);

        CreateDownloadZip::dispatch($userId, $this->filesToDownload, $zipName);

        return [
            'success' => true
        ];
    }

    private function getFileURL($file)
    {
        $sharedConfig = [
            'region'  => config('filesystems.disks.s3.region'),
            'version' => 'latest',
            'credentials' => [
                'key'    => config('filesystems.disks.s3.key'),
                'secret' => config('filesystems.disks.s3.secret'),
            ]
        ];

        $s3 = new \Aws\S3\S3Client($sharedConfig);

        $cmd = $s3->getCommand('GetObject', [
            'Bucket' => config('filesystems.disks.s3.bucket'),
            'Key' => $file->path
        ]);

        $request = $s3->createPresignedRequest($cmd, '+1 minute');

        return [
            'success' => true,
            'url' => (string) $request->getUri()
        ];
    }

    private function getFilesToDownload($user, $files)
    {
        $folders = [];
        $filesToDownload = [];

        foreach ($files as $file) {
            if ($file['type'] === 'folder') {
                $this->getFolderFiles(Folder::find($file['id']), 1);
                continue;
            }

            $file = File::select('id', 'status', 'aliased_folder_id', 'path')->where('id', $file['id'])->userViewable(['user' => $user])->first();

            if (!$file || $file->status === File::STATUS_PENDING) {
                continue;
            }

            if ($file->isAlias()) {
                $aliasFolder = $file->aliasFolder()->withoutGlobalScope(VisibleScope::class)->first();

                if (is_null($aliasFolder)) {
                    throw new \Exception('File is alias but cannot find folder with id: ' . $aliasFolder->aliased_folder_id);
                }

                $this->getFolderFiles($aliasFolder, 1);
                continue;
            }

            $this->addFile($file);
        }
    }

    private function getFolderFiles(Folder $folder, $depth = 0)
    {
        $filesToDownload = [];

        $files = $folder->files()->withoutGlobalScope(VisibleScope::class)->get();
        foreach ($files as $file) {
            if ($file->isAlias()) {
                $aliasFolder = $file->aliasFolder()->withoutGlobalScope(VisibleScope::class)->get();
                $this->getFolderFiles($aliasFolder->first(), $depth + 1);
                continue;
            }

            $this->addFile($file, $depth);
        }

        $folders = $folder->folders()->withoutGlobalScope(VisibleScope::class)->get();
        foreach ($folders as $folder) {
            $this->getFolderFiles($folder, $depth + 1);
        }
    }

    private function addFile($file, $depth = 0)
    {
        $file->depth = $depth;
        $this->filesToDownload[] = $file;
    }

    private function getZipName($user, $files)
    {
        $firstFile = $files[0];
        if ($firstFile['type'] === 'folder') {
            $folder = Folder::select('id','folder_id')->where('id', $firstFile['id'])->userViewable(['user' => $user])->first();
            if (isset($folder->folder_id)) {
                $parentFolder = Folder::select('id','name')->where('id', $folder->folder_id)->userViewable(['user' => $user])->first();
                return $parentFolder->name;
            }
            return 'VEVA';
        }
        $file = File::select('id', 'folder_id')->where('id', $firstFile['id'])->userViewable(['user' => $user])->first();
        if (isset($file->folder_id)) {
            $folder = Folder::select('id','name')->where('id', $file->folder_id)->userViewable(['user' => $user])->first();
            return $folder->name;
        }
        return 'VEVA';
    }
}
