<?php

namespace App\Console\Commands;

use App\Models\CreditRole;
use App\Models\Party;
use App\Models\Project;
use App\Models\Recording;
use App\Models\Session;
use App\Models\Song;
use Illuminate\Console\Command;

class UpdateAllCredits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'creidts:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Save all objects that have credits';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach (CreditRole::all() as $resource) {
            $resource->save();
        }

        foreach (Party::all() as $resource) {
            $resource->save();
        }

        foreach (Project::all() as $resource) {
            $resource->save();
        }

        foreach (Recording::all() as $resource) {
            $resource->save();
        }

        foreach (Session::all() as $resource) {
            $resource->save();
        }

        foreach (Song::all() as $resource) {
            $resource->save();
        }
    }
}
