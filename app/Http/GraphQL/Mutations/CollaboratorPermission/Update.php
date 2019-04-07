<?php

namespace App\Http\GraphQL\Mutations\CollaboratorPermission;

use App\Models\Collaborator;
use App\Models\CollaboratorPermission;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\DB;
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

        if (!$user->hasCollaboratorAccess()) {
            throw new AuthorizationException('User does not have the plan to access this functionality');
        }

        $input = array_get($args, 'input');
        $collaboratorId = array_get($input, 'collaborator_id');
        $permissions = array_get($input, 'permissions');

        $collaborator = Collaborator::where('id', $collaboratorId)->userUpdatable()->first();

        if (!$collaborator) {
            throw new AuthorizationException('The user does not have access to the collaborator');
        }

        DB::beginTransaction();

        try {
            $permissions = $this->processPermissions($permissions);
            $collaborator->permissions()->delete();
            $permissions = $collaborator->permissions()->saveMany($permissions);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }

        return [
            'permissions' => $permissions,
        ];
    }

    private function processPermissions(array $permissions): array
    {
        $levels = CollaboratorPermission::LEVELS;
        $processed = [];

        foreach ($permissions as $permission) {
            $permissionLevel = array_get($permission, 'level');
            $type = array_get($permission, 'type');

            if (!in_array($type, CollaboratorPermission::TYPES)) {
                continue;
            }

            // If we are provided with 'full', we'll give them
            // all permissions for that resource.
            if ($permissionLevel == 'full') {
                foreach ($levels as $level) {
                    // Ignore download permission from all types apart from files
                    if ($type !== 'file' && $level === 'download') {
                        continue;
                    }

                    $processed[] = new CollaboratorPermission(['type' => $type, 'level' => $level]);
                }

                continue;
            }

            // If we have download we'll also have read.
            if ($permissionLevel == 'download') {
                $processed[] = new CollaboratorPermission(['type' => $type, 'level' => 'read']);
            }

            // If the level isn't in the list, we'll skip
            if (!in_array($permissionLevel, $levels)) {
                continue;
            }

            // Otherwise we'll just add the permission to be created.
            $processed[] = new CollaboratorPermission($permission);
        }

        // Make sure we have a 'read' level for each type.
        foreach (CollaboratorPermission::TYPES as $type) {
            $hasRead = false;

            foreach ($processed as $processedPermission) {
                if ($processedPermission->type == $type && $processedPermission->level == 'read') {
                    $hasRead = true;
                    break;
                }
            }

            if (!$hasRead) {
                $processed[] = new CollaboratorPermission(['type' => $type, 'level' => 'read']);
            }
        }

        return $processed;
    }
}
