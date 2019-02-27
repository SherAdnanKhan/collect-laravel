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

        if (isset($args['input'])) {
            $input = $args['input'];

            $id = (int) $input['type'];
            $type = $input['type'];

            if (!in_array($type, ['folder', 'file'])) {
                throw new \Exception('Type is not valid.');
            }

            switch ($type) {
                case 'file':
                    File::destroy($id);
                    $success = true;
                    break;
                case 'folder':
                    Folder::destroy($id);
                    $success = true;
                    break;
                default:
                    break;
            }
        }

        return [
            'success' => $success,
        ];
    }
}
