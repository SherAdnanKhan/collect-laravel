<?php

namespace App\Http\GraphQL\Queries\File;

use App\Models\File;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Exceptions\AuthorizationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class GetPreview
{
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
        $id = (int) $args['id'];

        $file = File::where('id', $id)->userViewable()->first();

        if (!$file) {
            throw new AuthorizationException('Unable to find to file');
        }

        if (!$file->is_previewable) {
            throw new AuthorizationException('Unable to preview file');
        }

        $preview_file = $file->path;
        if ($file->transcoded_path) {
            $preview_file = $file->transcoded_path;
        }

        $s3 = new \Aws\S3\S3Client([
            'region'  => config('filesystems.disks.s3.region'),
            'version' => 'latest',
            'credentials' => [
                'key'    => config('filesystems.disks.s3.key'),
                'secret' => config('filesystems.disks.s3.secret'),
            ]
        ]);

        $cmd = $s3->getCommand('GetObject', [
            'Bucket' => config('filesystems.disks.s3.bucket'),
            'Key'    => $preview_file
        ]);

        $request = $s3->createPresignedRequest($cmd, '+15 minutes');

        return [
            'name' => $file->name,
            'type' => $file->type,
            'url' => (string)$request->getUri()
        ];
    }
}
