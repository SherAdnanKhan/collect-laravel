<?php

namespace App\Providers;

use App\Http\GraphQL\Directives\RenameInputDirective;
use App\Models\File;
use App\Models\Folder;
use App\Models\Person;
use App\Models\Project;
use App\Models\Recording;
use App\Models\Session;
use App\Models\Song;
use App\Observers\FolderObserver;
use App\Observers\ProjectObserver;
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
        Relation::morphMap([
            'file'      => File::class,
            'folder'    => Folder::class,
            'project'   => Project::class,
            'person'    => Person::class,
            'session'   => Session::class,
            'recording' => Recording::class,
            'song'      => Song::class,
        ]);

        Folder::observe(FolderObserver::class);
        Project::observe(ProjectObserver::class);
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
