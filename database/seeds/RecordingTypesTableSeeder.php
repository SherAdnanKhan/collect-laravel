<?php

use App\Models\RecordingType;
use Illuminate\Database\Seeder;

class RecordingTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            'MusicalWorkReadalongSoundRecording' => 'Musical Work Read along Sound Recording',
            'MusicalWorkSoundRecording' => 'Musical Work Sound Recording',
            'NonMusicalWorkReadalongSoundRecording' => 'Non Musical Work Read along Sound Recording',
            'NonMusicalWorkSoundRecording' => 'Non Musical Work Sound Recording',
            'SpokenWordSoundRecording' => 'Spoken Word Sound Recording',
            'Unknown' => 'Unknown',
            'UserDefined' => 'User Defined',
        ];

        foreach ($types as $key => $name) {
            RecordingType::create([
                'name'         => $name,
                'ddex_key'     => $key,
                'user_defined' => $key === 'UserDefined',
            ]);
        }
    }
}
