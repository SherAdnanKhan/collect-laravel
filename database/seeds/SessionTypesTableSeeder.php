<?php

use App\Models\SessionType;
use Illuminate\Database\Seeder;

class SessionTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            'ArtistVocals' => 'Artist Vocals',
            'DigitalEditing' => 'Digital Editing',
            'Demo' => 'Demo', //
            'Editing' => 'Editing',
            'LivePerformance' => 'Live Performance',
            'Mastering' => 'Mastering',
            'Mixing' => 'Mixing',
            'Overdub' => 'Overdub',
            'PreProduction' => 'Pre-Production',
            'Production' => 'Production',
            'Preservation' => 'Preservation', //
            'Project' => 'Project',
            'Remix' => 'Remix',
            'Tracking' => 'Tracking',
            'Transfer' => 'Transfer',
            'TransfersAndSafeties' => 'Transfers and Safeties',
            'Vocal' => 'Vocal',
        ];

        foreach ($types as $key => $name) {
            SessionType::create([
                'name'     => $name,
                'ddex_key' => $key,
            ]);
        }
    }
}
