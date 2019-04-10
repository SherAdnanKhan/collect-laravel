<?php

use App\Models\SongType;
use Illuminate\Database\Seeder;

class SongTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            'Adapted In Original Language' => 'AdaptedInOriginalLanguage',
            'Adapted Instrumental Work' => 'AdaptedInstrumentalWork',
            'Adapted With New Lyrics' => 'AdaptedWithNewLyrics',
            'Arranged With New Music' => 'ArrangedWithNewMusic',
            'Composite Musical Work' => 'CompositeMusicalWork',
            'Dramatico Musical Work' => 'DramaticoMusicalWork',
            'Lyric Removal' => 'LyricRemoval',
            'Lyric Replacement' => 'LyricReplacement',
            'Lyric Translation' => 'LyricTranslation',
            'Mashup' => 'Mashup',
            'Medley' => 'Medley',
            'Multimedia Production Work' => 'MultimediaProductionWork',
            'Musical Work Movement' => 'MusicalWorkMovement',
            'Musical Work With Samples' => 'MusicalWorkWithSamples',
            'Music Arrangement' => 'MusicArrangement',
            'Music Arrangement Of Text' => 'MusicArrangementOfText',
            'Original Lyrics Arrangement' => 'OriginalLyricsArrangement',
            'Original Music Adaptation' => 'OriginalMusicAdaptation',
            'Original Musical Work' => 'OriginalMusicalWork',
            'Potpourri' => 'Potpourri',
            'Production Music Library Work' => 'ProductionMusicLibraryWork',
            'Radio Production Work' => 'RadioProductionWork',
            'Theater Production Work' => 'TheaterProductionWork',
            'Tv Production Work' => 'TvProductionWork',
            'Unknown' => 'Unknown',
            'Unspecified Arrangement' => 'UnspecifiedArrangement',
            'Unspecified Musical Work Excerpt' => 'UnspecifiedMusicalWorkExcerpt',
            'User Defined' => 'UserDefined',
            'Video Production Work' => 'VideoProductionWork'
        ];

        foreach ($types as $name => $key) {
            SongType::create([
                'name' => $name,
                'ddex_key' => $key
            ]);
        }
    }
}
