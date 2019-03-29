<?php

namespace App\Http\GraphQL\Subscriptions;

use App\Contracts\UserAccessible;
use App\Models\Comment;
use App\Models\Subscription;
use App\Models\User;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Http\Request;
use Nuwave\Lighthouse\Schema\Types\GraphQLSubscription;
use Nuwave\Lighthouse\Subscriptions\Subscriber;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class CommentCreated extends GraphQLSubscription
{
    /**
     * Check if subscriber is allowed to listen to the subscription.
     *
     * @param  \Nuwave\Lighthouse\Subscriptions\Subscriber  $subscriber
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function authorize(Subscriber $subscriber, Request $request): bool
    {
        // Any user can subscribe to this.
        return !is_null($subscriber->context->user);
    }

    /**
     * Filter which subscribers should receive the subscription.
     *
     * @param  \Nuwave\Lighthouse\Subscriptions\Subscriber  $subscriber
     * @param  mixed  $root
     * @return bool
     */
    public function filter(Subscriber $subscriber, $root): bool
    {
        $user = $subscriber->context->user;

        // Don't need to get this for the user who made it.
        if (is_null($user) || $user->id == $root->user_id) {
            return false;
        }

        $query = $root->newQuery();

        // If the comment has the scopes
        if ($root instanceof UserAccessible) {
            $query = $query->userViewable(['user' => $user]);
        }

        // if a project id is provided we'll also filter by that.
        if (array_key_exists('projectId', $subscriber->args)) {
            $query = $query->where('project_id', array_get($subscriber->args, 'projectId'));
        }

        // if resource type is provided we'll filter by that.
        if (array_key_exists('resourceType', $subscriber->args)) {
            $query = $query->where('resource_type', array_get($subscriber->args, 'resourceType'));

            // And if there's a type we can also filter down by id.
            if (array_key_exists('resourceId', $subscriber->args)) {
                $query = $query->where('resource_id', array_get($subscriber->args, 'resourceId'));
            }
        }

        return $query->exists();
    }

    /**
     * Resolve the subscription.
     *
     * @param  \App\Models\Comment  $root
     * @param  mixed[]  $args
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context
     * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo
     * @return \App\Models\Comment
     */
    public function resolve($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Comment
    {
        return $root;
    }
}
