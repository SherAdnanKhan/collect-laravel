<?php

namespace App\Providers;

use App\Models\Song;
use App\Models\Party;
use App\Models\Folder;
use App\Models\Comment;
use App\Models\Project;
use App\Models\Session;
use App\Models\Recording;
use App\Models\Collaborator;
use App\Policies\SongPolicy;
use App\Policies\PartyPolicy;
use App\Policies\FolderPolicy;
use App\Policies\CommentPolicy;
use App\Policies\ProjectPolicy;
use App\Policies\SessionPolicy;
use App\Policies\RecordingPolicy;
use App\Policies\CollaboratorPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Project::class      => ProjectPolicy::class,
        Recording::class    => RecordingPolicy::class,
        Comment::class      => CommentPolicy::class,
        Session::class      => SessionPolicy::class,
        Collaborator::class => CollaboratorPolicy::class,
        Folder::class       => FolderPolicy::class,
        Song::class         => SongPolicy::class,
        Party::class        => PartyPolicy::class,
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
