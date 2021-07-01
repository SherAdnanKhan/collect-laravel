<?php

namespace App\Observers;

use App\Models\Comment;
use App\Models\File;
use App\Models\Folder;
use App\Models\User;
use App\Models\UserFavourite;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Log;

class UserObserver
{
    /**
     * Handle the folder "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(User $user)
    {
        $user->profile()->save(new UserProfile());

        /* Venues */
        $venue1 = $user->venues()->create([
            'name' => 'Southern Ground',
            'address' => ''
        ]);
        $venue2 = $user->venues()->create([
            'name' => 'VEVA Studios',
            'address' => ''
        ]);
        $venue3 = $user->venues()->create([
            'name' => 'VEVA Sample Studios',
            'address' => ''
        ]);

        /* Parties */
        $party5 = $user->parties()->create([
            'type' => 'organisation',
            'first_name' => 'VEVA Records'
        ]);
        $party6 = $user->parties()->create([
            'type' => 'person',
            'first_name' => 'VEVA',
            'last_name' => 'Artist'
        ]);
        $party7 = $user->parties()->create([
            'type' => 'organisation',
            'first_name' => 'Capitol Nashville'
        ]);
        $party8 = $user->parties()->create([
            'type' => 'person',
            'first_name' => 'Lori',
            'last_name' => 'McKenna'
        ]);
        $party9 = $user->parties()->create([
            'type' => 'person',
            'first_name' => 'Liz',
            'last_name' => 'Rose'
        ]);
        $party10 = $user->parties()->create([
            'type' => 'person',
            'first_name' => 'Hillary',
            'last_name' => 'Lindsey'
        ]);
        $party1 = $user->parties()->create([
            'type' => 'organisation',
            'first_name' => 'VEVA Sample Records',
            'comments' => 'This is a sample party, and these are sample party comments. VEVA isn\'t a record label.'
        ]);
        $party11 = $user->parties()->create([
            'type' => 'person',
            'title' => 'Mr.',
            'first_name' => 'Jack',
            'last_name' => 'Daniels',
            'birth_date' => '1875-01-01',
            'comments' => 'This is a sample party, and these are sample party comments. "Jack Daniels" is a very common name.'
        ]);
        $party2 = $user->parties()->create([
            'type' => 'person',
            'title' => 'Mr.',
            'first_name' => 'Sample',
            'last_name' => 'Artist',
            'suffix' => 'Jr.',
            'birth_date' => '1929-04-05',
            'comments' => 'This is a sample party, and these are sample party notes for a Sample Artist.'
        ]);
        $party3 = $user->parties()->create([
            'type' => 'person',
            'title' => 'Ms.',
            'first_name' => 'Sample',
            'last_name' => 'Engineer',
            'birth_date' => '1911-10-20',
            'comments' => 'This is a sample party for a sample engineer.'
        ]);
        $party12 = $user->parties()->create([
            'type' => 'person',
            'title' => 'Mr.',
            'first_name' => 'Sample Songwriter'
        ]);
        $party4 = $user->parties()->create([
            'type' => 'organisation',
            'first_name' => 'Sample Songwriter Publisher',
            'comments' => 'PRO: ASCAP Publisher CAE/IPI #: 222222200'
        ]);
        $party13 = $user->parties()->create([
            'type' => 'organisation',
            'first_name' => 'Sample Artist Publisher'
        ]);

        /* Party Addresses */
        $party1->addresses()->create([
            'line_1' => '123 Anytown Street',
            'city' => 'Nashville',
            'district' => 'TN',
            'postal_code' => '37210',
            'country_id' => '223'
        ]);
        $party2->addresses()->create([
            'line_1' => '122 Anytown Way',
            'city' => 'Nashville',
            'district' => 'TN',
            'postal_code' => '37210',
            'country_id' => '223'
        ]);
        $party3->addresses()->create([
            'line_1' => '123 Anytown Ct',
            'city' => 'Nashville',
            'district' => 'TN',
            'postal_code' => '37210',
            'country_id' => '223'
        ]);

        /* Party Contacts */
        $party1->contacts()->create([
            'name' => 'Sample Label Contact',
            'type' => 'email',
            'value' => 'hello@vevacollect.com'
        ]);
        $party2->contacts()->create([
            'type' => 'email',
            'value' => 'hello@vevacollect.com'
        ]);
        $party3->contacts()->create([
            'type' => 'email',
            'value' => 'hello@vevacollect.com'
        ]);
        $party4->contacts()->create([
            'name' => 'VEVA Demo',
            'type' => 'email',
            'value' => 'demo@vevacollect.com'
        ]);
        $party4->contacts()->create([
            'name' => 'VEVA Demo',
            'type' => 'phone',
            'value' => '+1(615)6156156'
        ]);

        /* Subscriptions */
        $user->subscriptions()->create([
            'name' => 'main',
            'stripe_id' => 'sub_Hh62vi7C4hNsQQ',
            'stripe_plan' => 'pro',
            'quantity' => 1
        ]);

        /* Projects */
        $project1 = $user->projects()->create([
            'name' => 'Demo VEVA Project',
            'description' => 'New LP',
            'total_storage_used' => 41985872,
            'label_id' => $party5->id,
            'main_artist_id' => $party6->id,
            'image' => '1598982250veva-main-banner-with-logo.jpg',
            'number' => 'VEVA'.substr(number_format(time() * mt_rand(),0,'',''),0,14)
        ]);
        $project2 = $user->projects()->create([
            'name' => 'Song ideas',
            'description' => '',
            'total_storage_used' => 0,
            'label_id' => $party5->id,
            'main_artist_id' => $party6->id,
            'image' => '',
            'number' => 'VEVA'.substr(number_format(time() * mt_rand(),0,'',''),0,14)
        ]);
        $project3 = $user->projects()->create([
            'name' => 'VEVA Collect Sample Project',
            'description' => 'This is a sample project, and this is a sample project description. Within this project are credits and files.',
            'total_storage_used' => 92484967,
            'label_id' => $party1->id,
            'main_artist_id' => $party2->id,
            'image' => '',
            'number' => 'VEVA'.substr(number_format(time() * mt_rand(),0,'',''),0,14)
        ]);

        /* Project Sessions */
        $session1Project2 = $project2->sessions()->create([
            'session_type_id' => 14,
            'venue_id' => $venue1->id,
            'name' => 'Tracking',
            'started_at' => '2019-05-28 00:00:00',
            'union_session' => 0,
            'analog_session' => 0,
            'drop_frame' => 0,
            'bitdepth' => 24,
            'samplerate' => 44100,
        ]);
        $session1Project3 = $project3->sessions()->create([
            'session_type_id' => 7,
            'venue_id' => $venue3->id,
            'name' => 'Sample Mixing Session',
            'description' => "This is a sample session and this is a sample session description. VEVA doesn''t have a studio. ",
            'started_at' => '2020-10-01 12:00:00',
            'union_session' => 0,
            'analog_session' => 0,
            'drop_frame' => 0,
            'venue_room' => 'Sample Venue Room 1',
            'bitdepth' => 24,
            'samplerate' => 96000,
        ]);
        $session2Project3 = $project3->sessions()->create([
            'session_type_id' => 6,
            'venue_id' => $venue3->id,
            'name' => 'Sample Mastering Session',
            'description' => 'This is a sample mastering session description.',
            'started_at' => '2020-10-03 12:00:00',
            'union_session' => 0,
            'analog_session' => 0,
            'drop_frame' => 0,
            'venue_room' => 'Sample Mastering Room',
            'bitdepth' => 24,
            'samplerate' => 96000,
        ]);
        $session3Project3 = $project3->sessions()->create([
            'session_type_id' => 14,
            'venue_id' => $venue3->id,
            'name' => 'Sample Tracking Session',
            'description' => 'This is a sample tracking session description. ',
            'started_at' => '2020-09-30 12:00:00',
            'union_session' => 0,
            'analog_session' => 0,
            'drop_frame' => 0,
            'venue_room' => 'Sample Tracking Room',
            'bitdepth' => 24,
            'samplerate' => 96000,
        ]);

        /* Session codes */
        $session1Project2->sessionCodes()->create([
            'code' => '036613',
            'expires_at' => now()->subDay(1)
        ]);

        /* Songs */
        $song1 = $user->songs()->create([
            'song_type_id' => 19,
            'title' => 'My New Song',
            'created_on' => '2020-09-01',
        ]);
        $song2 = $user->songs()->create([
            'song_type_id' => 19,
            'title' => 'Cry Pretty',
            'lyrics' => "I''m sorry, but I''m just a girl
                        Not usually the kind to show my heart to the world
                        I''m pretty good at keeping it together
                        I hold my composure, for worse or for better
                        So I apologize if you don''t like what you see
                        But sometimes my emotions get the best of me
                        And falling apart is as human as it gets
                        You can''t hide it, you can''t fight what the truth is
                        You can pretty lie and say it''s okay
                        You can pretty smile and just walk away
                        Pretty much fake your way through anything
                        But you can''t cry pretty
                        Oh no, you can''t dress it up in lace or rhinestones
                        It don''t matter if you''re in a crowd or home all alone
                        Yeah, it''s all the same when you''re looking in the mirror
                        A picture of pain, so let it flow like a river
                        You can pretty lie and say it''s okay
                        You can pretty smile and just walk away
                        Pretty much fake your way through anything
                        But you can''t cry pretty
                        You can''t turn off the flood when the dam breaks
                        When all your mascara is going to waste
                        When things get ugly, you just gotta face
                        That you can''t cry pretty
                        Oh no, you can''t
                        Ooh, yeah
                        You can''t turn off the flood when the dam breaks
                        When all your mascara is going to waste
                        When things get ugly, you just gotta face
                        That you can''t cry pretty
                        Yeah, yeah, yeah, yeah
                        No, you can''t cry
                        No, you can''t cry
                        Yeah, yeah, yeah, yeah, yeah
                        No, no, no, no, no, no, no, no, woo
                        You can''t cry
                        You can''t cry pretty, baby
                        Oh yeah, woo, woo
                        No, you can''t cry pretty, yeah",
        ]);
        $song3 = $user->songs()->create([
            'song_type_id' => 19,
            'iswc' => 'ISWC Number',
            'title' => 'This Is A Sample Song',
            'subtitle' => 'A Sample Song for VEVA Collect Users',
            'title_alt' => 'Sample Alternative Title',
            'subtitle_alt' => 'Sample Alternative Subtitle',
            'created_on' => '2020-10-01',
            'description' => 'This is a sample song, and this is a sample song description. ',
            'lyrics' => "This is my sample song
                        These are sample song lyrics
                        Aren''t they great
                        If you don''t mind, I say
                        They are

                        This is my sample song
                        These are sample song lyrics
                        Aren''t they great
                        If you don''t mind, I say
                        They are

                        Imagine the possibilities
                        when you collaborate in VEVA Collect
                        to write a song of your own
                        Then people will say
                        All about you, aren''t they great?
                        They are

                        This is my sample song
                        These are sample song lyrics
                        Aren''t they great
                        If you don''t mind, I say
                        They are'",
            'notes' => 'This is a sample song, and these are sample song notes.',
        ]);
        $song4 = $user->songs()->create([
            'song_type_id' => 19,
            'title' => 'Another Sample Song',
            'subtitle' => 'Another Sample Song Subtitle',
            'title_alt' => 'Another Sample Song Alternative Title',
            'subtitle_alt' => 'Another Sample Song Alternative Subtitle',
            'created_on' => '2020-11-06',
            'description' => 'This is the description of another sample song. ',
            'lyrics' => "This is another sample song
                        These are sample song lyrics
                        Aren''t they great
                        If you don''t mind, I say
                        They are

                        This is my sample song
                        These are sample song lyrics
                        Aren''t they great
                        If you don''t mind, I say
                        They are

                        Imagine the possibilities
                        when you collaborate in VEVA Collect
                        to write a song of your own
                        Then people will say
                        All about you, aren''t they great?
                        They are

                        This is my sample song
                        These are sample song lyrics
                        Aren''t they great
                        If you don''t mind, I say
                        They are",
            'notes' => 'These are the notes for another sample song. ',
        ]);

        /* Folders */
        $folder1Project2 = Folder::create([
            'project_id' => $project2->id,
            'user_id'    => $user->id,
            'name' => 'Recording: Cry Pretty',
            'depth' => 0,
            'readonly' => 1,
            'hidden' => 0,
        ]);
        $folder1Project1 = Folder::create([
            'project_id' => $project1->id,
            'user_id'    => $user->id,
            'name' => 'Recording: My New Song',
            'depth' => 0,
            'readonly' => 1,
            'hidden' => 0,
        ]);
        $folder1Project3 = Folder::create([
            'project_id' => $project3->id,
            'user_id'    => $user->id,
            'name' => 'Recording: Recording of This Is A Sample Song',
            'depth' => 0,
            'readonly' => 1,
            'hidden' => 0,
        ]);
        $folder2Project3 = Folder::create([
            'project_id' => $project3->id,
            'user_id'    => $user->id,
            'folder_id' => $folder1Project3->id,
            'name' => 'Sample Folder ',
            'depth' => 1,
            'readonly' => 0,
            'root_folder_id' => $folder1Project3->id,
            'hidden' => 0,
        ]);
        $folder3Project3 = Folder::create([
            'project_id' => $project3->id,
            'user_id'    => $user->id,
            'name' => 'Recording: Recording of Another Sample Song',
            'depth' => 0,
            'readonly' => 1,
            'hidden' => 0,
        ]);

        $project1UploadFiles = [
            'uploads/projects/d32bb14e71e175a626e45cccaccb32b5/1599591623dPbEf0WDy83RFlsA/  BGV 6 (Doubled)-1_02.R.wav',
            'uploads/projects/d32bb14e71e175a626e45cccaccb32b5/1599591639W8rbX37wNFf5Zaz9/  BGV 6 (Doubled)-1_02.L.wav',
        ];
        $project1TransFiles = [
            'transcoded/uploads/projects/d32bb14e71e175a626e45cccaccb32b5/1599591623dPbEf0WDy83RFlsA/  BGV 6 (Doubled)-1_02.R.wav.mp3',
            'transcoded/uploads/projects/d32bb14e71e175a626e45cccaccb32b5/1599591639W8rbX37wNFf5Zaz9/  BGV 6 (Doubled)-1_02.L.wav.mp3',
        ];
        $project3UploadFiles = [
            'uploads/projects/b1c747ab0057df75fcdeeab2bb712eb8/1603999331F52aRVp0yl1EO8Jq/Explaining Collaborators and Permissions.mp3',
            'uploads/projects/b1c747ab0057df75fcdeeab2bb712eb8/1603999339xNBTGRf7OS86d1mg/Explaining Sessions and the VEVA Check In App.mp3',
            'uploads/projects/b1c747ab0057df75fcdeeab2bb712eb8/16039993422pUISfby1mu48EXL/How to Add Parties in VEVA Collect.mp3',
            'uploads/projects/b1c747ab0057df75fcdeeab2bb712eb8/1603999346u69ZoiBGW1PIHdNK/How to Manage Collaborators.mp3',
            'uploads/projects/b1c747ab0057df75fcdeeab2bb712eb8/1603999351HPGTsc9XDp0fvemz/How to Upload and Share Files.mp3',
            'uploads/projects/b1c747ab0057df75fcdeeab2bb712eb8/1603999356sIHtb2kGmcKMW8dC/Managing Songs and Credits.mp3',
            'uploads/projects/b1c747ab0057df75fcdeeab2bb712eb8/1603999359z3aXZJNLA2lxWYq4/Managing Sound Recordings.mp3',
            'uploads/projects/b1c747ab0057df75fcdeeab2bb712eb8/1604079570sWPZDxSvA2owRdUC/VEVA Collect Sample Music File.mp3',
            'uploads/projects/b1c747ab0057df75fcdeeab2bb712eb8/1604087289jxHZvyUN6aP4lbeI/Bass DI.13_01.mp3',
            'uploads/projects/b1c747ab0057df75fcdeeab2bb712eb8/1604087300biY7PhUfIsa4N1we/BSS OLI.13_01.mp3',
            'uploads/projects/b1c747ab0057df75fcdeeab2bb712eb8/1604087306q7Fr3QHCfVsBZyJ5/CLAPS_01.L.mp3',
            'uploads/projects/b1c747ab0057df75fcdeeab2bb712eb8/1604087313JV7Wtp6jkuweq53m/CLAPS_01.R.mp3',
            'uploads/projects/b1c747ab0057df75fcdeeab2bb712eb8/1604087318NKTmUrJSavLAd3gk/CLAPS.dup1_01.L.mp3',
            'uploads/projects/b1c747ab0057df75fcdeeab2bb712eb8/1604087323YLtpqfm7ehFVKCS0/CLAPS.dup1_01.R.mp3',
            'uploads/projects/b1c747ab0057df75fcdeeab2bb712eb8/1604087329MY6uriNg8UTc2Zk7/Floor.13_01.mp3',
            'uploads/projects/b1c747ab0057df75fcdeeab2bb712eb8/16040873342VWEAsIkTi5pXw0Q/FOK.13_01.mp3',
            'uploads/projects/b1c747ab0057df75fcdeeab2bb712eb8/1604087338wWAbhBdnycUoCYSV/Hat.13_01.mp3',
        ];
        $project3TransFiles = [
            'transcoded/uploads/projects/b1c747ab0057df75fcdeeab2bb712eb8/1603999331F52aRVp0yl1EO8Jq/Explaining Collaborators and Permissions.mp3',
            'transcoded/uploads/projects/b1c747ab0057df75fcdeeab2bb712eb8/1603999339xNBTGRf7OS86d1mg/Explaining Sessions and the VEVA Check In App.mp3',
            'transcoded/uploads/projects/b1c747ab0057df75fcdeeab2bb712eb8/16039993422pUISfby1mu48EXL/How to Add Parties in VEVA Collect.mp3',
            'transcoded/uploads/projects/b1c747ab0057df75fcdeeab2bb712eb8/1603999346u69ZoiBGW1PIHdNK/How to Manage Collaborators.mp3',
            'transcoded/uploads/projects/b1c747ab0057df75fcdeeab2bb712eb8/1603999351HPGTsc9XDp0fvemz/How to Upload and Share Files.mp3',
            'transcoded/uploads/projects/b1c747ab0057df75fcdeeab2bb712eb8/1603999356sIHtb2kGmcKMW8dC/Managing Songs and Credits.mp3',
            'transcoded/uploads/projects/b1c747ab0057df75fcdeeab2bb712eb8/1603999359z3aXZJNLA2lxWYq4/Managing Sound Recordings.mp3',
            'transcoded/uploads/projects/b1c747ab0057df75fcdeeab2bb712eb8/1604079570sWPZDxSvA2owRdUC/VEVA Collect Sample Music File.mp3',
            'transcoded/uploads/projects/b1c747ab0057df75fcdeeab2bb712eb8/1604087289jxHZvyUN6aP4lbeI/Bass DI.13_01.mp3',
            'transcoded/uploads/projects/b1c747ab0057df75fcdeeab2bb712eb8/1604087300biY7PhUfIsa4N1we/BSS OLI.13_01.mp3',
            'transcoded/uploads/projects/b1c747ab0057df75fcdeeab2bb712eb8/1604087306q7Fr3QHCfVsBZyJ5/CLAPS_01.L.mp3',
            'transcoded/uploads/projects/b1c747ab0057df75fcdeeab2bb712eb8/1604087313JV7Wtp6jkuweq53m/CLAPS_01.R.mp3',
            'transcoded/uploads/projects/b1c747ab0057df75fcdeeab2bb712eb8/1604087318NKTmUrJSavLAd3gk/CLAPS.dup1_01.L.mp3',
            'transcoded/uploads/projects/b1c747ab0057df75fcdeeab2bb712eb8/1604087323YLtpqfm7ehFVKCS0/CLAPS.dup1_01.R.mp3',
            'transcoded/uploads/projects/b1c747ab0057df75fcdeeab2bb712eb8/1604087329MY6uriNg8UTc2Zk7/Floor.13_01.mp3',
            'transcoded/uploads/projects/b1c747ab0057df75fcdeeab2bb712eb8/16040873342VWEAsIkTi5pXw0Q/FOK.13_01.mp3',
            'transcoded/uploads/projects/b1c747ab0057df75fcdeeab2bb712eb8/1604087338wWAbhBdnycUoCYSV/Hat.13_01.mp3',
        ];
        $project2UploadFiles = [
            'uploads/projects/71f99c64bc13558c9e36605bd7e8a3e8/1617910329jDMaVkvNfnZUF0bS/Poster_Dark_You_Deserve_Credit_2021.pdf',
            'uploads/projects/71f99c64bc13558c9e36605bd7e8a3e8/1617910350QwhZ9REcvuONoqS7/SaveTheDate_Dark_You_Deserve_Credit_2021.jpg',
            'uploads/projects/71f99c64bc13558c9e36605bd7e8a3e8/1617910357m7X9ngCZudVyK8zL/Story_You_Deserve_Credit_2021.jpg',
            'uploads/projects/71f99c64bc13558c9e36605bd7e8a3e8/1617910363YrHc1dAw0FijOMSl/Square_You_Deserve_Credit_2021.jpg',
            'uploads/projects/71f99c64bc13558c9e36605bd7e8a3e8/1617910373BqpU9s0LdbYvmnwQ/LOGO_You_Deserve_Credit_White.png',
            'uploads/projects/71f99c64bc13558c9e36605bd7e8a3e8/1617910378ZRqzKtSbYInD6ohV/LOGO_You_Deserve_Credit_Dark.png',
        ];

        $bucket = config('filesystems.disks.s3.bucket');
        $s3 = $this->getS3Client();

        $file1Project1 = File::create([
            'project_id' => $project1->id,
            'user_id'    => $user->id,
            'type' => 'wav',
            'name' => '  BGV 6 (Doubled)-1_02.R.wav',
            'path' => $this->addProjectFiles($s3, $bucket, $project1, 'uploads/projects', $project1UploadFiles[0]),
            'transcoded_path' => $this->addProjectFiles($s3, $bucket, $project1, 'transcoded/uploads/projects', $project1TransFiles[0]),
            'bitrate' => 0,
            'bitdepth' => 0,
            'samplerate' => 0,
            'duration' => 0,
            'numchans' => 0,
            'size' => 20992936,
            'status' => 'complete',
            'hidden' => 0,
        ]);

        $file2Project1 = File::create([
            'project_id' => $project1->id,
            'user_id'    => $user->id,
            'type' => 'wav',
            'name' => '  BGV 6 (Doubled)-1_02.L.wav',
            'path' => $this->addProjectFiles($s3, $bucket, $project1, 'uploads/projects', $project1UploadFiles[1]),
            'transcoded_path' => $this->addProjectFiles($s3, $bucket, $project1, 'transcoded/uploads/projects', $project1TransFiles[1]),
            'bitrate' => 0,
            'bitdepth' => 0,
            'samplerate' => 0,
            'duration' => 0,
            'numchans' => 0,
            'size' => 20992936,
            'status' => 'complete',
            'hidden' => 0,
        ]);

        $file1Project3 = File::create([
            'project_id' => $project3->id,
            'user_id'    => $user->id,
            'type' => 'mp3',
            'name' => 'Explaining Collaborators and Permissions.mp3',
            'path' => $this->addProjectFiles($s3, $bucket, $project3, 'uploads/projects', $project3UploadFiles[0]),
            'transcoded_path' => $this->addProjectFiles($s3, $bucket, $project3, 'transcoded/uploads/projects', $project3TransFiles[0]),
            'bitrate' => 256000,
            'bitdepth' => 0,
            'samplerate' => 44100,
            'duration' => 224249000000,
            'numchans' => 2,
            'size' => 7251990,
            'status' => 'complete',
            'hidden' => 0,
        ]);

        $file2Project3 = File::create([
            'project_id' => $project3->id,
            'user_id'    => $user->id,
            'type' => 'mp3',
            'name' => 'Explaining Sessions and the VEVA Check In App.mp3',
            'path' => $this->addProjectFiles($s3, $bucket, $project3, 'uploads/projects', $project3UploadFiles[1]),
            'transcoded_path' => $this->addProjectFiles($s3, $bucket, $project3, 'transcoded/uploads/projects', $project3TransFiles[1]),
            'bitrate' => 256000,
            'bitdepth' => 0,
            'samplerate' => 44100,
            'duration' => 119900000000,
            'numchans' => 2,
            'size' => 3922993,
            'status' => 'complete',
            'hidden' => 0,
        ]);

        $file3Project3 = File::create([
            'project_id' => $project3->id,
            'user_id'    => $user->id,
            'type' => 'mp3',
            'name' => 'How to Add Parties in VEVA Collect.mp3',
            'path' => $this->addProjectFiles($s3, $bucket, $project3, 'uploads/projects', $project3UploadFiles[2]),
            'transcoded_path' => $this->addProjectFiles($s3, $bucket, $project3, 'transcoded/uploads/projects', $project3TransFiles[2]),
            'bitrate' => 256000,
            'bitdepth' => 0,
            'samplerate' => 44100,
            'duration' => 74471000000,
            'numchans' => 2,
            'size' => 2464189,
            'status' => 'complete',
            'hidden' => 0,
        ]);

        $file4Project3 = File::create([
            'project_id' => $project3->id,
            'user_id'    => $user->id,
            'type' => 'mp3',
            'name' => 'How to Manage Collaborators.mp3',
            'path' => $this->addProjectFiles($s3, $bucket, $project3, 'uploads/projects', $project3UploadFiles[3]),
            'transcoded_path' => $this->addProjectFiles($s3, $bucket, $project3, 'transcoded/uploads/projects', $project3TransFiles[3]),
            'bitrate' => 256000,
            'bitdepth' => 0,
            'samplerate' => 44100,
            'duration' => 76376000000,
            'numchans' => 2,
            'size' => 2496336,
            'status' => 'complete',
            'hidden' => 0,
        ]);

        $file5Project3 = File::create([
            'project_id' => $project3->id,
            'user_id'    => $user->id,
            'type' => 'mp3',
            'name' => 'How to Upload and Share Files.mp3',
            'path' => $this->addProjectFiles($s3, $bucket, $project3, 'uploads/projects', $project3UploadFiles[4]),
            'transcoded_path' => $this->addProjectFiles($s3, $bucket, $project3, 'transcoded/uploads/projects', $project3TransFiles[4]),
            'bitrate' => 256000,
            'bitdepth' => 0,
            'samplerate' => 44100,
            'duration' => 294050000000,
            'numchans' => 2,
            'size' => 9494735,
            'status' => 'complete',
            'hidden' => 0,
        ]);

        $file6Project3 = File::create([
            'project_id' => $project3->id,
            'user_id'    => $user->id,
            'type' => 'mp3',
            'name' => 'Managing Songs and Credits.mp3',
            'path' => $this->addProjectFiles($s3, $bucket, $project3, 'uploads/projects', $project3UploadFiles[5]),
            'transcoded_path' => $this->addProjectFiles($s3, $bucket, $project3, 'transcoded/uploads/projects', $project3TransFiles[5]),
            'bitrate' => 256000,
            'bitdepth' => 0,
            'samplerate' => 44100,
            'duration' => 90936000000,
            'numchans' => 2,
            'size' => 2970326,
            'status' => 'complete',
            'hidden' => 0,
        ]);

        $file7Project3 = File::create([
            'project_id' => $project3->id,
            'user_id'    => $user->id,
            'type' => 'mp3',
            'name' => 'Managing Sound Recordings.mp3',
            'path' => $this->addProjectFiles($s3, $bucket, $project3, 'uploads/projects', $project3UploadFiles[6]),
            'transcoded_path' => $this->addProjectFiles($s3, $bucket, $project3, 'transcoded/uploads/projects', $project3TransFiles[6]),
            'bitrate' => 256000,
            'bitdepth' => 0,
            'samplerate' => 44100,
            'duration' => 98060000000,
            'numchans' => 2,
            'size' => 3211026,
            'status' => 'complete',
            'hidden' => 0,
        ]);

        $file8Project3 = File::create([
            'project_id' => $project3->id,
            'user_id'    => $user->id,
            'type' => 'mp3',
            'name' => 'VEVA Collect Sample Music File.mp3',
            'path' => $this->addProjectFiles($s3, $bucket, $project3, 'uploads/projects', $project3UploadFiles[7]),
            'transcoded_path' => $this->addProjectFiles($s3, $bucket, $project3, 'transcoded/uploads/projects', $project3TransFiles[7]),
            'bitrate' => 192000,
            'bitdepth' => 0,
            'samplerate' => 44100,
            'duration' => 18597000000,
            'numchans' => 2,
            'size' => 453391,
            'status' => 'complete',
            'hidden' => 0,
            'folder_id' => $folder1Project3->id
        ]);

        $file9Project3 = File::create([
            'project_id' => $project3->id,
            'user_id'    => $user->id,
            'type' => 'mp3',
            'name' => 'Bass DI.13_01.mp3',
            'path' => $this->addProjectFiles($s3, $bucket, $project3, 'uploads/projects', $project3UploadFiles[8]),
            'transcoded_path' => $this->addProjectFiles($s3, $bucket, $project3, 'transcoded/uploads/projects', $project3TransFiles[8]),
            'bitrate' => 192000,
            'bitdepth' => 0,
            'samplerate' => 44100,
            'duration' => 278491000000,
            'numchans' => 2,
            'size' => 6691109,
            'status' => 'complete',
            'hidden' => 0,
            'folder_id' => $folder2Project3->id
        ]);

        $file10Project3 = File::create([
            'project_id' => $project3->id,
            'user_id'    => $user->id,
            'type' => 'mp3',
            'name' => 'BSS OLI.13_01.mp3',
            'path' => $this->addProjectFiles($s3, $bucket, $project3, 'uploads/projects', $project3UploadFiles[9]),
            'transcoded_path' => $this->addProjectFiles($s3, $bucket, $project3, 'transcoded/uploads/projects', $project3TransFiles[9]),
            'bitrate' => 192000,
            'bitdepth' => 0,
            'samplerate' => 44100,
            'duration' => 278491000000,
            'numchans' => 2,
            'size' => 6691109,
            'status' => 'complete',
            'hidden' => 0,
            'folder_id' => $folder2Project3->id
        ]);

        $file11Project3 = File::create([
            'project_id' => $project3->id,
            'user_id'    => $user->id,
            'type' => 'mp3',
            'name' => 'CLAPS_01.L.mp3',
            'path' => $this->addProjectFiles($s3, $bucket, $project3, 'uploads/projects', $project3UploadFiles[10]),
            'transcoded_path' => $this->addProjectFiles($s3, $bucket, $project3, 'transcoded/uploads/projects', $project3TransFiles[10]),
            'bitrate' => 192000,
            'bitdepth' => 0,
            'samplerate' => 44100,
            'duration' => 278491000000,
            'numchans' => 2,
            'size' => 6691109,
            'status' => 'complete',
            'hidden' => 0,
            'folder_id' => $folder2Project3->id
        ]);

        $file12Project3 = File::create([
            'project_id' => $project3->id,
            'user_id'    => $user->id,
            'type' => 'mp3',
            'name' => 'CLAPS_01.R.mp3',
            'path' => $this->addProjectFiles($s3, $bucket, $project3, 'uploads/projects', $project3UploadFiles[11]),
            'transcoded_path' => $this->addProjectFiles($s3, $bucket, $project3, 'transcoded/uploads/projects', $project3TransFiles[11]),
            'bitrate' => 192000,
            'bitdepth' => 0,
            'samplerate' => 44100,
            'duration' => 278491000000,
            'numchans' => 2,
            'size' => 6691109,
            'status' => 'complete',
            'hidden' => 0,
            'folder_id' => $folder2Project3->id
        ]);

        $file13Project3 = File::create([
            'project_id' => $project3->id,
            'user_id'    => $user->id,
            'type' => 'mp3',
            'name' => 'CLAPS.dup1_01.L.mp3',
            'path' => $this->addProjectFiles($s3, $bucket, $project3, 'uploads/projects', $project3UploadFiles[12]),
            'transcoded_path' => $this->addProjectFiles($s3, $bucket, $project3, 'transcoded/uploads/projects', $project3TransFiles[12]),
            'bitrate' => 192000,
            'bitdepth' => 0,
            'samplerate' => 44100,
            'duration' => 278491000000,
            'numchans' => 2,
            'size' => 6691109,
            'status' => 'complete',
            'hidden' => 0,
            'folder_id' => $folder2Project3->id
        ]);

        $file14Project3 = File::create([
            'project_id' => $project3->id,
            'user_id'    => $user->id,
            'type' => 'mp3',
            'name' => 'CLAPS.dup1_01.R.mp3',
            'path' => $this->addProjectFiles($s3, $bucket, $project3, 'uploads/projects', $project3UploadFiles[13]),
            'transcoded_path' => $this->addProjectFiles($s3, $bucket, $project3, 'transcoded/uploads/projects', $project3TransFiles[13]),
            'bitrate' => 192000,
            'bitdepth' => 0,
            'samplerate' => 44100,
            'duration' => 278491000000,
            'numchans' => 2,
            'size' => 6691109,
            'status' => 'complete',
            'hidden' => 0,
            'folder_id' => $folder2Project3->id
        ]);

        $file15Project3 = File::create([
            'project_id' => $project3->id,
            'user_id'    => $user->id,
            'type' => 'mp3',
            'name' => 'Floor.13_01.mp3',
            'path' => $this->addProjectFiles($s3, $bucket, $project3, 'uploads/projects', $project3UploadFiles[14]),
            'transcoded_path' => $this->addProjectFiles($s3, $bucket, $project3, 'transcoded/uploads/projects', $project3TransFiles[14]),
            'bitrate' => 192000,
            'bitdepth' => 0,
            'samplerate' => 44100,
            'duration' => 278491000000,
            'numchans' => 2,
            'size' => 6691109,
            'status' => 'complete',
            'hidden' => 0,
            'folder_id' => $folder2Project3->id
        ]);

        $file16Project3 = File::create([
            'project_id' => $project3->id,
            'user_id'    => $user->id,
            'type' => 'mp3',
            'name' => 'FOK.13_01.mp3',
            'path' => $this->addProjectFiles($s3, $bucket, $project3, 'uploads/projects', $project3UploadFiles[15]),
            'transcoded_path' => $this->addProjectFiles($s3, $bucket, $project3, 'transcoded/uploads/projects', $project3TransFiles[15]),
            'bitrate' => 192000,
            'bitdepth' => 0,
            'samplerate' => 44100,
            'duration' => 278491000000,
            'numchans' => 2,
            'size' => 6691109,
            'status' => 'complete',
            'hidden' => 0,
            'folder_id' => $folder2Project3->id
        ]);

        $file17Project3 = File::create([
            'project_id' => $project3->id,
            'user_id'    => $user->id,
            'type' => 'mp3',
            'name' => 'Hat.13_01.mp3',
            'path' => $this->addProjectFiles($s3, $bucket, $project3, 'uploads/projects', $project3UploadFiles[16]),
            'transcoded_path' => $this->addProjectFiles($s3, $bucket, $project3, 'transcoded/uploads/projects', $project3TransFiles[16]),
            'bitrate' => 192000,
            'bitdepth' => 0,
            'samplerate' => 44100,
            'duration' => 278491000000,
            'numchans' => 2,
            'size' => 6691109,
            'status' => 'complete',
            'hidden' => 0,
            'folder_id' => $folder2Project3->id
        ]);

        $file1Project2 = File::create([
            'project_id' => $project2->id,
            'user_id'    => $user->id,
            'type' => 'pdf',
            'name' => 'Poster_Dark_You_Deserve_Credit_2021.pdf',
            'path' => $this->addProjectFiles($s3, $bucket, $project2, 'uploads/projects', $project2UploadFiles[0]),
            'transcoded_path' => '',
            'bitrate' => 0,
            'bitdepth' => 0,
            'samplerate' => 0,
            'duration' => 0,
            'numchans' => 0,
            'size' => 9215616,
            'status' => 'complete',
            'hidden' => 0,
        ]);

        $file2Project2 = File::create([
            'project_id' => $project2->id,
            'user_id'    => $user->id,
            'type' => 'jpg',
            'name' => 'SaveTheDate_Dark_You_Deserve_Credit_2021.jpg',
            'path' => $this->addProjectFiles($s3, $bucket, $project2, 'uploads/projects', $project2UploadFiles[1]),
            'transcoded_path' => '',
            'bitrate' => 0,
            'bitdepth' => 0,
            'samplerate' => 0,
            'duration' => 0,
            'numchans' => 0,
            'size' => 3001108,
            'status' => 'complete',
            'hidden' => 0,
        ]);

        $file3Project2 = File::create([
            'project_id' => $project2->id,
            'user_id'    => $user->id,
            'type' => 'jpg',
            'name' => 'Story_You_Deserve_Credit_2021.jpg',
            'path' => $this->addProjectFiles($s3, $bucket, $project2, 'uploads/projects', $project2UploadFiles[2]),
            'transcoded_path' => '',
            'bitrate' => 0,
            'bitdepth' => 0,
            'samplerate' => 0,
            'duration' => 0,
            'numchans' => 0,
            'size' => 571198,
            'status' => 'complete',
            'hidden' => 0,
        ]);

        $file4Project2 = File::create([
            'project_id' => $project2->id,
            'user_id'    => $user->id,
            'type' => 'jpg',
            'name' => 'Square_You_Deserve_Credit_2021.jpg',
            'path' => $this->addProjectFiles($s3, $bucket, $project2, 'uploads/projects', $project2UploadFiles[3]),
            'transcoded_path' => '',
            'bitrate' => 0,
            'bitdepth' => 0,
            'samplerate' => 0,
            'duration' => 0,
            'numchans' => 0,
            'size' => 400074,
            'status' => 'complete',
            'hidden' => 0,
        ]);

        $file5Project2 = File::create([
            'project_id' => $project2->id,
            'user_id'    => $user->id,
            'type' => 'png',
            'name' => 'LOGO_You_Deserve_Credit_White.png',
            'path' => $this->addProjectFiles($s3, $bucket, $project2, 'uploads/projects', $project2UploadFiles[4]),
            'transcoded_path' => '',
            'bitrate' => 0,
            'bitdepth' => 0,
            'samplerate' => 0,
            'duration' => 0,
            'numchans' => 0,
            'size' => 128321,
            'status' => 'complete',
            'hidden' => 0,
        ]);

        $file6Project2 = File::create([
            'project_id' => $project2->id,
            'user_id'    => $user->id,
            'type' => 'png',
            'name' => 'LOGO_You_Deserve_Credit_Dark.png',
            'path' => $this->addProjectFiles($s3, $bucket, $project2, 'uploads/projects', $project2UploadFiles[5]),
            'transcoded_path' => '',
            'bitrate' => 0,
            'bitdepth' => 0,
            'samplerate' => 0,
            'duration' => 0,
            'numchans' => 0,
            'size' => 135743,
            'status' => 'complete',
            'hidden' => 0,
        ]);

        /* Recordings */
        $recording1 = $project1->recordings()->create([
            'party_id' => $party6->id,
            'song_id' => $song1->id,
            'name' => 'My New Song',
            'recording_type_id' => 2,
        ]);
        $recording1->folder_id = $folder1Project1->id;
        $recording1->save();

        $recording2 = $project2->recordings()->create([
            'party_id' => $party6->id,
            'song_id' => $song2->id,
            'name' => 'Cry Pretty',
            'recording_type_id' => 2,
            'language_id' => 40,
        ]);
        $recording2->folder_id = $folder1Project2->id;
        $recording2->save();

        $recording3 = $project3->recordings()->create([
            'party_id' => $party2->id,
            'song_id' => $song3->id,
            'name' => 'Recording of This Is A Sample Song',
            'subtitle' => 'Subtitle for This Is A Sample Song',
            'recording_type_id' => 2,
            'description' => 'This is a sample recording of "This Is A Sample Song" written and recorded by "Sample Artist," and this is a sample recording description. ',
            'version' => '1',
            'recorded_on' => '2020-10-01',
            'mixed_on' => '2020-10-02',
            'duration' => 80,
            'language_id' => 40,
            'key_signature' => 'D minor',
            'time_signature' => '4/4',
            'tempo' => 120,
        ]);
        $recording3->folder_id = $folder1Project3->id;
        $recording3->save();

        $recording4 = $project3->recordings()->create([
            'party_id' => $party2->id,
            'song_id' => $song4->id,
            'name' => 'Recording of Another Sample Song',
            'subtitle' => 'Subtitle for The Recording of Another Sample Song',
            'recording_type_id' => 2,
            'description' => 'This is the description of the recording of Another Sample Song. ',
            'version' => '2',
            'recorded_on' => '2020-11-04',
            'mixed_on' => '2020-11-06',
            'duration' => 80,
            'language_id' => 40,
            'key_signature' => 'D minor',
            'time_signature' => '4/4',
            'tempo' => 160,
        ]);
        $recording4->folder_id = $folder3Project3->id;
        $recording4->save();

        /* Party Credits */
        $credit1 = $party8->credits()->create([
            'contribution_id' => $song2->id,
            'contribution_type' => 'song',
            'credit_role_id' => 269,
            'performing' => 0,
            'split' => null,
        ]);

        $credit2 = $party9->credits()->create([
            'contribution_id' => $song2->id,
            'contribution_type' => 'song',
            'credit_role_id' => 269,
            'performing' => 0,
            'split' => null,
        ]);

        $credit3 = $party10->credits()->create([
            'contribution_id' => $song2->id,
            'contribution_type' => 'song',
            'credit_role_id' => 269,
            'performing' => 0,
            'split' => null,
        ]);

        $credit4 = $party11->credits()->create([
            'contribution_id' => $song3->id,
            'contribution_type' => 'song',
            'credit_role_id' => 269,
            'performing' => 0,
            'split' => 100.0,
            'deleted_at' => '2020-10-29 19:41:19'
        ]);

        $credit5 = $party2->credits()->create([
            'contribution_id' => $song3->id,
            'contribution_type' => 'song',
            'credit_role_id' => 269,
            'performing' => 0,
            'split' => 25.0,
        ]);

        $credit6 = $party2->credits()->create([
            'contribution_id' => $recording3->id,
            'contribution_type' => 'recording',
            'credit_role_id' => 614,
            'performing' => 0,
            'split' => null,
        ]);

        $credit7 = $party2->credits()->create([
            'contribution_id' => $session3Project3->id,
            'contribution_type' => 'session',
            'credit_role_id' => 406,
            'performing' => 0,
            'instrument_id' => 247,
            'split' => null,
        ]);

        $credit8 = $party2->credits()->create([
            'contribution_id' => $project3->id,
            'contribution_type' => 'project',
            'credit_role_id' => 618,
            'performing' => 0,
            'split' => null,
        ]);

        $credit9 = $party3->credits()->create([
            'contribution_id' => $session2Project3->id,
            'contribution_type' => 'session',
            'credit_role_id' => 400,
            'performing' => 0,
            'split' => null,
        ]);

        $credit10 = $party3->credits()->create([
            'contribution_id' => $session1Project3->id,
            'contribution_type' => 'session',
            'credit_role_id' => 403,
            'performing' => 0,
            'split' => null,
        ]);

        $credit11 = $party3->credits()->create([
            'contribution_id' => $session3Project3->id,
            'contribution_type' => 'session',
            'credit_role_id' => 463,
            'performing' => 0,
            'split' => null,
        ]);

        $credit12 = $party12->credits()->create([
            'contribution_id' => $song3->id,
            'contribution_type' => 'song',
            'credit_role_id' => 268,
            'performing' => 0,
            'split' => 25.0,
        ]);

        $credit13 = $party4->credits()->create([
            'contribution_id' => $song3->id,
            'contribution_type' => 'song',
            'credit_role_id' => 299,
            'performing' => 0,
            'split' => 25.0,
        ]);

        $credit14 = $party13->credits()->create([
            'contribution_id' => $song3->id,
            'contribution_type' => 'song',
            'credit_role_id' => 299,
            'performing' => 0,
            'split' => 25.0,
        ]);

        /* Credits to Projects */
        $project3->credits()->attach($credit5->id);
        $project3->credits()->attach($credit6->id);
        $project3->credits()->attach($credit7->id);
        $project3->credits()->attach($credit8->id);
        $project3->credits()->attach($credit9->id);
        $project3->credits()->attach($credit10->id);
        $project3->credits()->attach($credit11->id);
        $project3->credits()->attach($credit12->id);
        $project3->credits()->attach($credit13->id);
        $project3->credits()->attach($credit14->id);

        /* Sessions to Recordings */
        $recording1->sessions()->attach($session1Project2->id);
        $recording3->sessions()->attach($session1Project3->id);
        $recording3->sessions()->attach($session2Project3->id);
        $recording3->sessions()->attach($session3Project3->id);

        /* Comments */
        Comment::create([
            'project_id' => $project3->id,
            'user_id' => $user->id,
            'resource_id' => $project3->id,
            'resource_type' => 'project',
            'message' => 'This is a sample comment!',
        ]);

        Comment::create([
            'project_id' => $project3->id,
            'user_id' => $user->id,
            'resource_id' => $project3->id,
            'resource_type' => 'project',
            'message' => 'Comments can be used to approve mixes, ask for changes, or congratulate a collaborator on a great job.',
        ]);

        Comment::create([
            'project_id' => $project3->id,
            'user_id' => $user->id,
            'resource_id' => $file2Project3->id,
            'resource_type' => 'file',
            'message' => 'Sounds Great - but can you bring the bass up by 2db in the chorus.',
        ]);

        Comment::create([
            'project_id' => $project3->id,
            'user_id' => $user->id,
            'resource_id' => $file2Project3->id,
            'resource_type' => 'file',
            'message' => 'yep - its done! ',
        ]);

        /* User Favourites  */
        UserFavourite::create([
            'user_id' => $user->id,
            'resource_id' => $project2->id,
            'resource_type' => 'project',
        ]);

        UserFavourite::create([
            'user_id' => $user->id,
            'resource_id' => $project3->id,
            'resource_type' => 'project',
        ]);
    }

    private function getS3Client()
    {
        $config = config('filesystems.disks.s3');
        return new \Aws\S3\S3Client([
            'region'  => $config['region'],
            'version' => 'latest',
            'credentials' => [
                'key'    => $config['key'],
                'secret' => $config['secret'],
            ]
        ]);
    }

    private function addProjectFiles($s3, $bucket, $project, $prefix, $fileSource): string
    {
        $uploadPath = $project->getUploadFolderPath();
        $filename = explode('/', $fileSource);
        $key = "{$prefix}/{$uploadPath}" . time() . substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 16) . '/' . end($filename);
        $s3->copyObject([
            'Bucket' => $bucket,
            'Key' => $key,
            'CopySource' => "{$bucket}/{$fileSource}",
        ]);
        return $key;
    }
}
