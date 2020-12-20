<?php

namespace App\Http\GraphQL\Queries\Share;

use App\Models\Share;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Exceptions\GenericException;
use Nuwave\Lighthouse\Exceptions\AuthorizationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class ByFile
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
        $input = array_get($args, 'input');
        $id = $input['id'];
        $type = $input['type'];

        if (!in_array($type, ['file', 'folder'])) {
            throw new GenericException('Invalid file type!');
        }

        $key = ($type == 'file') ? 'file_id' : 'folder_id';

        $shares = Share::whereIn('id', function($query) use ($key, $id){
            $query->select('share_id')
                  ->from('share_files')
                  ->where($key, $id);
        })->userViewable()->get();

        if (!$shares) {
            throw new AuthorizationException('Unable to find shares');
        }

        return $shares;
    }
}
