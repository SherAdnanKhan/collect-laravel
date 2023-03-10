<?php

namespace App\Http\GraphQL\Subscriptions;

use Nuwave\Lighthouse\Support\Http\Controllers\SubscriptionController;

class Router
{
    /**
     * Register the routes for pusher based subscriptions.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function pusher($router): void
    {
        // We want to apply the graphql middleware to the subscription auth
        // endpoint so we've got CORS.
        $router->middleware('graphql')->group(function($router) {
            $router->post('graphql/subscriptions/auth', [
                'as' => 'lighthouse.subscriptions.auth',
                'uses' => SubscriptionController::class.'@authorize',
            ]);

            $router->post('graphql/subscriptions/webhook', [
                'as' => 'lighthouse.subscriptions.auth',
                'uses' => SubscriptionController::class.'@webhook',
            ]);
        });
    }
}
