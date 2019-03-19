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
            'Editing' => 'Editing',
            'LivePerformance' => 'Live Performance',
            'LivePerformance' => 'Live Performance',
            'Mastering' => 'Mastering',
            'Mixing' => 'Mixing',
            'Overdub' => 'Overdub',
            'PreProduction' => 'Pre-Production',
            'Production' => 'Production',
            'Project' => 'Project',
            'Remix' => 'Remix',
            'Tracking' => 'Tracking',
            'Transfer' => 'Transfer',
            'TransfersAndSafeties' => 'Transfers & Safeties',
            'TransfersAndSafeties' => 'Transfers and Safeties',
            'Vocal' => 'Vocal',
        ];

        foreach ($types as $key => $name) {
            SessionType::create([
                'name' => $name
            ]);
        }
    }
}
