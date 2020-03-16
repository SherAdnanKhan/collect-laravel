<?php

namespace App\Http\GraphQL\Queries;

use App\Models\DownloadJob;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Validator;

class ValidateDownload
{
    /**
     * @param $rootValue
     * @param array $args
     * @param \Nuwave\Lighthouse\Support\Contracts\GraphQLContext|null $context
     * @param \GraphQL\Type\Definition\ResolveInfo $resolveInfo
     * @return array
     */
    public function resolve($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {
        $uuid = $args['uuid'];

        $download_job = DownloadJob::find($uuid);
        if (!$download_job || $download_job->hasExpired() || !$download_job->complete) {
            return [
                'expired' => true
            ];
        }

        $url = Storage::disk('s3')->temporaryUrl(
            substr($download_job->path, 1), $download_job->expires_at
        );

        $download_job->download_count = $download_job->download_count + 1;
        $download_job->save();

        return [
            'expired' => false,
            'url' => $url
        ];
    }
}
