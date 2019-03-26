<?php

namespace App\Providers;

use App\Http\GraphQL\Directives\RenameInputDirective;
use App\Models\Collaborator;
use App\Models\Credit;
use App\Models\File;
use App\Models\Folder;
use App\Models\Party;
use App\Models\Project;
use App\Models\Recording;
use App\Models\Session;
use App\Models\Song;
use App\Models\User;
use App\Observers\CollaboratorObserver;
use App\Observers\CreditObserver;
use App\Observers\EventLogObserver;
use App\Observers\FolderObserver;
use App\Observers\ProjectObserver;
use App\Observers\UserObserver;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \DB::listen(function($executed) {
            info($executed->sql);
        });

        Relation::morphMap([
            'file'      => File::class,
            'folder'    => Folder::class,
            'project'   => Project::class,
            'party'     => Party::class,
            'person'    => Party::class,
            'session'   => Session::class,
            'recording' => Recording::class,
            'song'      => Song::class,
        ]);

        Folder::observe(FolderObserver::class);
        Project::observe(ProjectObserver::class);
        Credit::observe(CreditObserver::class);
        Collaborator::observe(CollaboratorObserver::class);
        User::observe(UserObserver::class);

        Project::observe(EventLogObserver::class);
        Recording::observe(EventLogObserver::class);
        Session::observe(EventLogObserver::class);
        Collaborator::observe(EventLogObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
