<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\Project;
use App\Models\Recording;
use App\Policies\CommentPolicy;
use App\Policies\ProjectPolicy;
use App\Policies\RecordingPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Project::class   => ProjectPolicy::class,
        Recording::class => RecordingPolicy::class,
        Comment::class   => CommentPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
