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
    private $files_to_download = [];

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
        $this->getFilesToDownload($args['files']);

        if (!isset($this->files_to_download[0])) {
            return [
                'success' => false
            ];
        }

        Log::info('files_to_download', $this->files_to_download);

        if (!isset($this->files_to_download[1]) && $this->files_to_download[0]->depth === 0) {
            return $this->getFileURL($this->files_to_download[0]);
        }

        CreateDownloadZip::dispatch(auth()->user()->id, $this->files_to_download);

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
            'url' => (string)$request->getUri()
        ];
    }

    private function getFilesToDownload($files)
    {
        $folders = [];
        $files_to_download = [];

        foreach ($files as $file) {
            if ($file['type'] === 'folder') {
                $this->getFolderFiles(Folder::find($file['id']), 1);
                continue;
            }

            $file = File::where('id', $file['id'])->userViewable()->first();

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
        $files_to_download = [];

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
        $this->files_to_download[] = $file;
    }
}
