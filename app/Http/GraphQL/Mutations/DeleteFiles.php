<?php

namespace App\Http\GraphQL\Mutations;

use App\Models\File;
use App\Models\Folder;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Exceptions\AuthenticationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * Handle the deletion of files.
 */
class DeleteFiles
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
        $success = false;
        foreach ($args['files'] as $file) {
            if ($file['type'] === 'folder') {
                $folder = Folder::where('id', $file['id'])->userDeletable()->first();
                if (!$folder) {
                    continue;
                }

                $folder->delete();
                $success = true;
                continue;
            }

            $file = File::where('id', $file['id'])->userDeletable()->first();
            if (!$file) {
                continue;
            }

            $file->delete();
            $success = true;
        }

        return [
            'success' => $success,
        ];
    }
}
