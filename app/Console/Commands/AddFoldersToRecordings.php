<?php

namespace App\Console\Commands;

use App\Models\Folder;
use App\Models\Recording;
use Illuminate\Console\Command;

class AddFoldersToRecordings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recordings:addfolders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create folders for recordings';

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
        $recordings = Recording::whereNull('folder_id')->get();
        foreach ($recordings as $recording) {
            $folder = Folder::create([
                'project_id' => $recording->project_id,
                'user_id'    => $recording->project->user_id,
                'name'       => sprintf('Recording: %s', $recording->name),
                'readonly'   => true
            ]);

            $recording->folder_id = $folder->id;
            $recording->save();
        }
    }
}
