<?php

namespace App\Http\GraphQL\Mutations\CollaboratorPermission;

use App\Models\Collaborator;
use App\Models\CollaboratorPermission;
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
        $user = auth()->user();

        $input = array_get($args, 'input');
        $collaboratorId = array_get($input, 'collaborator_id');
        $permissions = array_get($input, 'permissions');

        $collaborator = Collaborator::where('id', $collaboratorId)->userUpdatable()->first();

        if (!$collaborator) {
            throw new AuthorizationException('The user does not have access to the collaborator');
        }

        $permissions = $this->processPermissions($permissions);

        // Delete all other permissions
        $collaborator->permissions()->delete();

        return [
            'permissions' => $collaborator->permissions()->saveMany($permissions),
        ];
    }

    private function processPermissions(array $permissions): array
    {
        $levels = CollaboratorPermission::LEVELS;
        $processed = [];

        foreach ($permissions as $permission) {
            $permissionLevel = array_get($permission, 'level');

            // If we are provided with 'full', we'll give them
            // all permissions for that resource.
            if ($permissionLevel == 'full') {
                foreach ($levels as $level) {
                    $processed[] = new CollaboratorPermission(['type' => $permission['type'], 'level' => $level]);
                }

                continue;
            }

            // If we have download we'll also have read.
            if ($permissionLevel == 'download') {
                $processed[] = new CollaboratorPermission(['type' => $permission['type'], 'level' => 'read']);
            }

            // If the level isn't in the list, we'll skip
            if (!in_array($permissionLevel, $levels)) {
                continue;
            }

            // Otherwise we'll just add the permission to be created.
            $processed[] = new CollaboratorPermission($permission);
        }

        return $processed;
    }
}
