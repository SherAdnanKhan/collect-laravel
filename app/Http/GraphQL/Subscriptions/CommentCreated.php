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
        return true;
        // Any user can subscribe to this.
        // return !is_null($subscriber->context->user);
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
        return true;
        // $user = $subscriber->context->user;

        // if ($root instanceof UserAccessible) {
        //     return $root->newQuery()->scopeUserViewable(['user' => $user])->exists();
        // }

        // return false;
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
