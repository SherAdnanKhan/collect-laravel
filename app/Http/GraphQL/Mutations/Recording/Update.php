<?php

namespace App\Http\GraphQL\Mutations\Recording;

use App\Models\Folder;
use App\Models\Recording;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Exceptions\AuthorizationException;
use Nuwave\Lighthouse\Exceptions\GenericException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class Update
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
        $input = $args['input'];
        $id = (int) array_get($args, 'input.id');

        $recording = Recording::where('id', $id)->userUpdatable()->first();

        if (!$recording) {
            throw new AuthorizationException('Unable to find recording to update');
        }

        if (strpos($input['duration'], ':') !== false) {
            $durationParts = explode(':', $input['duration'], 3);
            $duration = 0;
            if (count($durationParts) === 3) {
                $duration = ((int)$durationParts[0] * 3600) + ((int)$durationParts[1] * 60) + (int)$durationParts[2];
            } elseif (count($durationParts) === 2) {
                $duration = ((int)$durationParts[0] * 60) + (int)$durationParts[1];
            } else {
                $duration = (int)$durationParts[0];
            }
            $input['duration'] = $duration;
        }

        $saved = $recording->fill($input)->save();

        if (!$saved) {
            throw new GenericException('Error saving recording');
        }

        Folder::find($recording->folder_id)->update([
            'name' => sprintf('Recording: %s', $recording->name)
        ]);

        return $recording;
    }
}
