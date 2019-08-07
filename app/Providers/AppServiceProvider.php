<?php

namespace App\Providers;

use App\Http\GraphQL\Directives\RenameInputDirective;
use App\Models\Collaborator;
use App\Models\Comment;
use App\Models\Credit;
use App\Models\EventLog;
use App\Models\File;
use App\Models\Folder;
use App\Models\Party;
use App\Models\Project;
use App\Models\Recording;
use App\Models\Session;
use App\Models\Song;
use App\Models\User;
use App\Observers\CollaboratorObserver;
use App\Observers\CommentObserver;
use App\Observers\CreditObserver;
use App\Observers\EventLogObserver;
use App\Observers\EventLoggableObserver;
use App\Observers\FavouredObserver;
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
            'file'         => File::class,
            'folder'       => Folder::class,
            'project'      => Project::class,
            'party'        => Party::class,
            'person'       => Party::class,
            'session'      => Session::class,
            'recording'    => Recording::class,
            'song'         => Song::class,
            'collaborator' => Collaborator::class,
            'comment'      => Comment::class,
        ]);

        Collaborator::observe(CollaboratorObserver::class);
        Comment::observe(CommentObserver::class);
        Credit::observe(CreditObserver::class);
        Folder::observe(FolderObserver::class);
        Project::observe(ProjectObserver::class);
        User::observe(UserObserver::class);

        Collaborator::observe(EventLoggableObserver::class);
        Comment::observe(EventLoggableObserver::class);
        Project::observe(EventLoggableObserver::class);
        Recording::observe(EventLoggableObserver::class);
        Session::observe(EventLoggableObserver::class);

        EventLog::observe(EventLogObserver::class);

        File::observe(FavouredObserver::class);
        Folder::observe(FavouredObserver::class);
        Project::observe(FavouredObserver::class);
        Recording::observe(FavouredObserver::class);
        Session::observe(FavouredObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Nexmo\Client', function() {
            return new \Nexmo\Client(
                new \Nexmo\Client\Credentials\Basic(
                    config('services.nexmo.key'),
                    config('services.nexmo.secret')
                )
            );
        });

        // $this->app->bind('App\Util\TwoFactorAuthentication', function() {
        //     return new \App\Util\TwoFactorAuthentication()
        // });
    }
}
