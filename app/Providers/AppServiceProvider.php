<?php

namespace App\Providers;

use App\Http\GraphQL\Directives\RenameInputDirective;
use App\Models\File;
use App\Models\Person;
use App\Models\Project;
use App\Models\Recording;
use App\Models\Session;
use App\Models\Song;
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
            'project'   => Project::class,
            'person'    => Person::class,
            'session'   => Session::class,
            'recording' => Recording::class,
            'song'      => Song::class,
        ]);
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
