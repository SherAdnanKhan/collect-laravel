<?php

namespace App\Observers;

use App\Models\Folder;

class FolderObserver
{
    /**
     * Handle the folder "deleting" event.
     *
     * @param  \App\Folder  $folder
     * @return void
     */
    public function deleting(Folder $folder)
    {
        // When we "delete" a folder we should delete all
        // folders inside of it as well as files.

        // Important! We iterate over the children here because we want to
        // leverage the Observers attached to them.

        $folder->allFolders()->each(function($subFolder) {
            $subFolder->delete();
        });

        $folder->allFiles()->each(function($file) {
            $file->delete();
        });
    }
}
