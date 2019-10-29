<?php

namespace App\Console\Commands;

use App\Models\Folder;
use App\Models\Recording;
use Illuminate\Console\Command;

class AddFolderRootIds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'folders:addroot';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add root folder ids to folders';

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
        $folders = Folder::whereNull('folder_id')->get();
        foreach ($folders as $folder) {
            $this->updateFolders($folder, true);
        }
    }

    private function updateFolders(Folder $parentFolder, $root = false)
    {
        foreach ($parentFolder->folders as $folder) {
            $folder->root_folder_id = ($root ? $parentFolder->id : $parentFolder->root_folder_id);
            $folder->save();

            $this->updateFolders($folder);
        }
    }
}
