<?php

use App\Models\Country;
use App\Models\Folder;
use App\Models\Song;
use Illuminate\Database\Seeder;

class AddFolderToExistingSongsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $songs = Song::withTrashed()->get();
        foreach ($songs as $song) {
            if (!isset($song->folder_id)) {
                $folder = Folder::create([
                    'user_id'    => $song->user->id,
                    'name'       => sprintf('Song: %s', $song->title),
                    'readonly'   => true
                ]);
                $song->folder_id = $folder->id;
                $song->save();
            }
        }
    }
}
