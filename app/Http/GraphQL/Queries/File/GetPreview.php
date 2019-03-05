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

        if (!$file || !$file->transcoded_path) {
            throw new AuthorizationException('Unable to find to file');
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
            'Key'    => $file->transcoded_path
        ]);

        $request = $s3->createPresignedRequest($cmd, '+1 minute');

        return [
            'name' => $file->name,
            'url' => (string)$request->getUri()
        ];
    }
}
