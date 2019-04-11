<?php

use App\Models\VersionType;
use Illuminate\Database\Seeder;

class VersionTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            'AcapellaVersion'     => 'Acapella Version',
            'AlbumVersion'        => 'Album Version',
            'AlternativeVersion'  => 'Alternative Version',
            'CleanVersion'        => 'Clean Version',
            'DemoVersion'         => 'Demo Version',
            'EditedVersion'       => 'Edited Version',
            'InstrumentalVersion' => 'Instrumental Version',
            'KaraokeVersion'      => 'Karaoke Version',
            'LiveVersion'         => 'Live Version',
            'MixVersion'          => 'Mix Version',
            'MonoVersion'         => 'Mono Version',
            'RadioVersion'        => 'Radio Version',
            'RemixVersion'        => 'Remix Version',
            'SessionVersion'      => 'Session Version',
            'SingleVersion'       => 'Single Version',
            'StereoVersion'       => 'Stereo Version',
            'UserDefined'         => 'User Defined',
        ];


        foreach ($types as $key => $name) {
            VersionType::create([
                'name'         => $name,
                'ddex_key'     => $key,
                'user_defined' => $key === 'UserDefined',
            ]);
        }
    }
}
