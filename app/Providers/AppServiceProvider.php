<?php

namespace App\Providers;

use App\Http\GraphQL\Directives\RenameInputDirective;
use App\Models\Collaborator;
use App\Models\Comment;
use App\Models\Credit;
use App\Models\CreditRole;
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
use App\Observers\CreditableObserver;
use App\Observers\EventLogObserver;
use App\Observers\EventLoggableObserver;
use App\Observers\FavouredObserver;
use App\Observers\FileObserver;
use App\Observers\FolderObserver;
use App\Observers\ProjectObserver;
use App\Observers\SongObserver;
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
        // \DB::listen(function($executed) {
        //     info($executed->sql);
        // });

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
        File::observe(FileObserver::class);
        Folder::observe(FolderObserver::class);
        Project::observe(ProjectObserver::class);
        User::observe(UserObserver::class);

        CreditRole::observe(CreditableObserver::class);
        Party::observe(CreditableObserver::class);
        Project::observe(CreditableObserver::class);
        Recording::observe(CreditableObserver::class);
        Session::observe(CreditableObserver::class);
        Song::observe(CreditableObserver::class);

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
        $this->app->bind('Vonage\Client', function() {
            return new \Vonage\Client(
                new \Vonage\Client\Credentials\Basic(
                    config('services.vonage.key'),
                    config('services.vonage.secret')
                )
            );
        });

        // $this->app->bind('App\Util\TwoFactorAuthentication', function() {
        //     return new \App\Util\TwoFactorAuthentication()
        // });
    }
}
