<?php

namespace App\Http\GraphQL\Mutations;

use App\Jobs\CreateDownloadZip;
use App\Models\File;
use App\Models\Folder;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Exceptions\AuthenticationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

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

        if (!isset($this->files_to_download[1]) && $this->files_to_download[0]['depth'] === 0) {
            return $this->getFileURL($this->files_to_download[0]['id']);
        }

        CreateDownloadZip::dispatch(auth()->user()->id, $this->files_to_download);

        return [
            'success' => true
        ];
    }

    private function getFileURL($id)
    {
        $file = File::find($id);

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

            $this->addFile($file->id);
        }
    }

    private function getFolderFiles(Folder $folder, $depth = 0)
    {
        $files_to_download = [];
        foreach ($folder->files as $file) {
            $this->addFile($file->id, $depth);
        }

        foreach ($folder->folders as $folder) {
            $this->getFolderFiles($folder, $depth + 1);
        }
    }

    private function addFile($id, $depth = 0)
    {
        $this->files_to_download[] = [
            'id' => $id,
            'depth' => $depth
        ];
    }
}
