<?php

use App\Models\Instrument;
use Illuminate\Database\Seeder;

class InstrumentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $instruments = [
            'AcousticBassGuitar' => 'Acoustic Bass Guitar',
            'AcousticGuitar' => 'Acoustic Guitar',
            'AcousticPiano' => 'Acoustic Piano',
            'AfricanHarp' => 'African Harp',
            'AfricanPercussion' => 'African Percussion',
            'AgogoBells' => 'Agogo Bells',
            'AkaiMPC1000' => 'Akai MPC-1000',
            'AkaiS1000' => 'Akai S1000',
            'Alboka' => 'Alboka',
            'Alpenhorn' => 'Alpenhorn',
            'AltoClarinet' => 'Alto Clarinet',
            'AltoCrumhorn' => 'Alto Crumhorn',
            'AltoFlute' => 'Alto Flute',
            'AltoHorn' => 'Alto Horn',
            'AltoRecorder' => 'Alto Recorder',
            'AltoSackbut' => 'Alto Sackbut',
            'AltoSaxophone' => 'Alto Saxophone',
            'AltoShawm' => 'Alto Shawm',
            'AltoTrombone' => 'Alto Trombone',
            'AltoViol' => 'Alto Viol',
            'AltoVoice' => 'Alto Voice',
            'AnalogueDrumMachine' => 'Analogue Drum Machine',
            'AnalogueSynthesizer' => 'Analogue Synthesizer',
            'AndeanHarp' => 'Andean Harp',
            'Angklung' => 'Angklung',
            'Anvil' => 'Anvil',
            'Apito' => 'Apito',
            'AppomatoxBugle' => 'Appomatox Bugle',
            'ArchLute' => 'Arch Lute',
            'ArcoAcousticBass' => 'Arco Acoustic Bass',
            'Arghul' => 'Arghul',
            'ArpSolinaStringEnsemble' => 'Arp Solina String Ensemble',
            'Atumpan' => 'Atumpan',
            'Aulochrome' => 'Aulochrome',
            'Autoharp' => 'Autoharp',
            'BabyBass' => 'Baby Bass',
            'Baglama' => 'Baglama',
            'Bagpipes' => 'Bagpipes',
            'BahianGuitar' => 'Bahian Guitar',
            'BajoSexto' => 'Bajo Sexto',
            'Balafon' => 'Balafon',
            'Balalaika' => 'Balalaika',
            'BaldwinFunMachine' => 'Baldwin Fun Machine',
            'Bandoneon' => 'Bandoneon',
            'Bandura' => 'Bandura',
            'Bandurria' => 'Bandurria',
            'Banhu' => 'Banhu',
            'Banjo' => 'Banjo',
            'BanjoGuitar' => 'Banjo Guitar',
            'Banjolin' => 'Banjolin',
            'Bansuri' => 'Bansuri',
            'BaritoneGuitar' => 'Baritone Guitar',
            'BaritoneHorn' => 'Baritone Horn',
            'BaritoneOboe' => 'Baritone Oboe',
            'BaritoneSaxophone' => 'Baritone Saxophone',
            'BaritoneVoice' => 'Baritone Voice',
            'BaroqueBassoon' => 'Baroque Bassoon',
            'BaroqueCello' => 'Baroque Cello',
            'BaroqueClarinet' => 'Baroque Clarinet',
            'BaroqueFlute' => 'Baroque Flute',
            'BaroqueGuitar' => 'Baroque Guitar',
            'BaroqueOboe' => 'Baroque Oboe',
            'BaroqueRecorder' => 'Baroque Recorder',
            'BaroqueViola' => 'Baroque Viola',
            'BaroqueViolin' => 'Baroque Violin',
            'BarrelOrgan' => 'Barrel Organ',
            'Baryton' => 'Baryton',
            'BassBanjo' => 'Bass Banjo',
            'BassBaritoneVoice' => 'Bass Baritone Voice',
            'BassCittern' => 'Bass Cittern',
            'BassClarinet' => 'Bass Clarinet',
            'BassDrum(Concert)' => 'Bass Drum (Concert)',
            'BassDulcian' => 'Bass Dulcian',
            'BassetClarinet' => 'Basset Clarinet',
            'BassetHorn' => 'Basset Horn',
            'BassFlute' => 'Bass Flute',
            'BassGuitar' => 'Bass Guitar',
            'BassHarmonica' => 'Bass Harmonica',
            'BassoDaBraccio' => 'Basso DaBraccio',
            'Bassoon' => 'Bassoon',
            'BassPedals' => 'Bass Pedals',
            'BassRebec' => 'Bass Rebec',
            'BassRecorder' => 'Bass Recorder',
            'BassSackbut' => 'Bass Sackbut',
            'BassSaxophone' => 'Bass Saxophone',
            'BassShawm' => 'Bass Shawm',
            'BassTrombone' => 'Bass Trombone',
            'BassTrumpet' => 'Bass Trumpet',
            'BassTuba' => 'Bass Tuba',
            'BassViol' => 'Bass Viol',
            'BassVoice' => 'Bass Voice',
            'Bata' => 'Bata',
            'Bawu' => 'Bawu',
            'BellTree' => 'Bell Tree',
            'Bendir' => 'Bendir',
            'Berimbau' => 'Berimbau',
            'BicyclePump' => 'Bicycle Pump',
            'BigBand' => 'Big Band',
            'BinghiDrum' => 'Binghi Drum',
            'BirdWhistle' => 'Bird Whistle',
            'Biwa' => 'Biwa',
            'Bodhran' => 'Bodhran',
            'BodyPercussion' => 'Body Percussion',
            'Bombard' => 'Bombard',
            'Bombo' => 'Bombo',
            'BomboLeguero' => 'Bombo Leguero',
            'Bones' => 'Bones',
            'Bongos' => 'Bongos',
            'Bontempi' => 'Bontempi',
            'BosunsWhistle' => 'Bosuns Whistle',
            'Bottles' => 'Bottles',
            'Bouzouki' => 'Bouzouki',
            'BoyVoice' => 'Boy Voice',
            'Bozoq' => 'Bozoq',
            'BrassBand' => 'Brass Band',
            'BrassInstrument' => 'Brass Instrument',
            'BrassSection' => 'Brass Section',
            'Breakbeat' => 'Breakbeat',
            'Brushes' => 'Brushes',
            'BufoBass' => 'Bufo Bass',
            'Bugle' => 'Bugle',
            'Cabasa' => 'Cabasa',
            'Caixa' => 'Caixa',
            'Caja' => 'Caja',
            'Cajon' => 'Cajon',
            'Calabash' => 'Calabash',
            'Calliope' => 'Calliope',
            'Carillon' => 'Carillon',
            'Castanet' => 'Castanet',
            'Cavaquinho' => 'Cavaquinho',
            'Caxixi' => 'Caxixi',
            'Celesta' => 'Celesta',
            'Cello' => 'Cello',
            'CelloBanjo' => 'Cello Banjo',
            'CelticHarp' => 'Celtic Harp',
            'Chalumeau' => 'Chalumeau',
            'Chamberlin' => 'Chamberlin',
            'ChamberOrchestra' => 'Chamber Orchestra',
            'ChapmanStick' => 'Chapman Stick',
            'Charango' => 'Charango',
            'ChildrensBackgroundVocalist' => 'Childrens Background Vocalist',
            'ChildrensChoir' => 'Childrens Choir',
            'ChildVoice' => 'Child Voice',
            'Chimes' => 'Chimes',
            'ChinaCymbal' => 'China Cymbal',
            'ChiryaTarang' => 'Chirya Tarang',
            'Chocalho' => 'Chocalho',
            'Choir' => 'Choir',
            'ChromaticButtonAccordion' => 'Chromatic Button Accordion',
            'ChromaticHarmonica' => 'Chromatic Harmonica',
            'Cimbalom' => 'Cimbalom',
            'Cimbasso' => 'Cimbasso',
            'Citole' => 'Citole',
            'Cittern' => 'Cittern',
            'Clapstick' => 'Clapstick',
            'Clarinet' => 'Clarinet',
            'ClarinoTrumpet' => 'Clarino Trumpet',
            'Claves' => 'Claves',
            'ClaviaNordLead' => 'Clavia Nord Lead',
            'Clavichord' => 'Clavichord',
            'Clavinet' => 'Clavinet',
            'Claypot' => 'Claypot',
            'Comb' => 'Comb',
            'ComputermusicSynthesis' => 'Computermusic Synthesis',
            'ConcertHarp' => 'Concert Harp',
            'Concertina' => 'Concertina',
            'ConchShell' => 'Conch Shell',
            'Congas' => 'Congas',
            'ContraAltoClarinet' => 'Contra Alto Clarinet',
            'ContrabassClarinet' => 'Contrabass Clarinet',
            'Contrabassoon' => 'Contrabassoon',
            'ContrabassRecorder' => 'Contrabass Recorder',
            'ContrabassSarrusophone' => 'Contrabass Sarrusophone',
            'ContrabassSaxophone' => 'Contrabass Saxophone',
            'ContrabassTrombone' => 'Contrabass Trombone',
            'ContrabassVoice' => 'Contrabass Voice',
            'ContraltoVoice' => 'Contralto Voice',
            'Cordovox' => 'Cordovox',
            'Cornet' => 'Cornet',
            'Cornetto' => 'Cornetto',
            'CountertenorVoice' => 'Countertenor Voice',
            'CountryGroup' => 'Country Group',
            'Cowbell' => 'Cowbell',
            'CrashCymbal' => 'Crash Cymbal',
            'Craviola' => 'Craviola',
            'Crotales' => 'Crotales',
            'CrumarRoadrunner2' => 'Crumar Roadrunner 2',
            'Crumhorn' => 'Crumhorn',
            'Crwth' => 'Crwth',
            'Cuatro' => 'Cuatro',
            'Cuica' => 'Cuica',
            'Cumbus' => 'Cumbus',
            'Cymbal(Suspended)' => 'Cymbal (Suspended)',
            'Cymbals' => 'Cymbals',
            'Daf' => 'Daf',
            'Damaru' => 'Damaru',
            'DanBau' => 'Dan Bau',
            'DanTranh' => 'Dan Tranh',
            'Davul' => 'Davul',
            'Dayereh' => 'Dayereh',
            'Dectet' => 'Dectet',
            'Defi' => 'Defi',
            'Dhol' => 'Dhol',
            'Dholak' => 'Dholak',
            'DiatonicButtonAccordion' => 'Diatonic Button Accordion',
            'Dictophone' => 'Dictophone',
            'Didgeridoo' => 'Didgeridoo',
            'DigitalSynthesizer' => 'Digital Synthesizer',
            'Dilruba' => 'Dilruba',
            'Diple' => 'Diple',
            'Dizi' => 'Dizi',
            'Djembe' => 'Djembe',
            'DjScratching' => 'Dj Scratching',
            'DobroGuitar' => 'Dobro Guitar',
            'Doepfer' => 'Doepfer',
            'Dohol' => 'Dohol',
            'Dombra' => 'Dombra',
            'Domra' => 'Domra',
            'DoubleBass' => 'Double Bass',
            'DoublebassViol' => 'Doublebass Viol',
            'DoubleHarp' => 'Double Harp',
            'DoubleViolin' => 'Double Violin',
            'Doumbek' => 'Doumbek',
            'Dranyen' => 'Dranyen',
            'Drehleier' => 'Drehleier',
            'DrumController' => 'Drum Controller',
            'DrumKat' => 'Drum Kat',
            'DrumKit' => 'Drum Kit',
            'DrumMachine' => 'Drum Machine',
            'DrumSample' => 'Drum Sample',
            'DrumSticks' => 'Drum Sticks',
            'Duduk' => 'Duduk',
            'Duet' => 'Duet',
            'Duggi' => 'Duggi',
            'Dulcian' => 'Dulcian',
            'Dulcitone' => 'Dulcitone',
            'Dungchen' => 'Dungchen',
            'Dunun' => 'Dunun',
            'Dutar' => 'Dutar',
            'Dzuddahord' => 'Dzuddahord',
            'Ektara' => 'Ektara',
            'Electric5StringBass' => 'Electric 5-String Bass',
            'Electric6StringBass' => 'Electric 6-String Bass',
            'Electric6StringViolin' => 'Electric 6-String Violin',
            'ElectricBassGuitar' => 'Electric Bass Guitar',
            'ElectricCello' => 'Electric Cello',
            'ElectricGuitar' => 'Electric Guitar',
            'ElectricHarp' => 'Electric Harp',
            'ElectricMandolin' => 'Electric Mandolin',
            'ElectricOrgan' => 'Electric Organ',
            'ElectricPiano' => 'Electric Piano',
            'ElectricSitar' => 'Electric Sitar',
            'ElectricSlideGuitar' => 'Electric Slide Guitar',
            'ElectricUprightBass' => 'Electric Upright Bass',
            'ElectricViola' => 'Electric Viola',
            'ElectricViolin' => 'Electric Violin',
            'ElectroAcousticHurdyGurdy' => 'Electro Acoustic Hurdy Gurdy',
            'ElectronicBagpipes' => 'Electronic Bagpipes',
            'ElectronicGroup' => 'Electronic Group',
            'ElectronicWindInstrument' => 'Electronic Wind Instrument',
            'Electronium' => 'Electronium',
            'ElephantBell' => 'Elephant Bell',
            'EmsVSC3' => 'EmsVSC3',
            'EnglishHorn' => 'English Horn',
            'Ennanga' => 'Ennanga',
            'EpinetteDesVosges' => 'Epinette Des Vosges',
            'Erhu' => 'Erhu',
            'Esraj' => 'Esraj',
            'Euphonium' => 'Euphonium',
            'FairlightCMI' => 'Fairlight CMI',
            'Farfisa' => 'Farfisa',
            'FemaleBackgroundVocalist' => 'Female Background Vocalist',
            'FemaleChoir' => 'Female Choir',
            'FemaleVoice' => 'Female Voice',
            'FenderBass' => 'Fender Bass',
            'FenderJazzBass' => 'Fender Jazz Bass',
            'FenderJazzmaster' => 'Fender Jazzmaster',
            'FenderPrecisionBass' => 'Fender Precision Bass',
            'FenderStratocaster' => 'Fender Stratocaster',
            'FenderTelecaster' => 'Fender Telecaster',
            'Fiddle' => 'Fiddle',
            'Fife' => 'Fife',
            'FingerClicks' => 'Finger Clicks',
            'FingerCymbals' => 'Finger Cymbals',
            'FingerSnaps' => 'Finger Snaps',
            'Fiscorn' => 'Fiscorn',
            'Flabiol' => 'Flabiol',
            'Flageolet' => 'Flageolet',
            'Flexatone' => 'Flexatone',
            'FloorToms' => 'Floor Toms',
            'Floyera' => 'Floyera',
            'Flugelhorn' => 'Flugelhorn',
            'Flute' => 'Flute',
            'FolkGroup' => 'Folk Group',
            'FolkHarp' => 'Folk Harp',
            'FolkloricPercussion' => 'Folkloric Percussion',
            'FootStomp' => 'Foot Stomp',
            'Fortepiano' => 'Fortepiano',
            'FrenchHorn' => 'French Horn',
            'FretlessBassGuitar' => 'Fretless Bass Guitar',
            'Frippertronics' => 'Frippertronics',
            'Frog' => 'Frog',
            'FryingPanGuitar' => 'Frying Pan Guitar',
            'Fujara' => 'Fujara',
            'Gadulka' => 'Gadulka',
            'Gambang' => 'Gambang',
            'Gamelan' => 'Gamelan',
            'Ganga' => 'Ganga',
            'Gardon' => 'Gardon',
            'Gasba' => 'Gasba',
            'Gayageum' => 'Gayageum',
            'Gemshorn' => 'Gemshorn',
            'GermanFlute' => 'German Flute',
            'Ghaita' => 'Ghaita',
            'Ghaychak' => 'Ghaychak',
            'GibsonFirebird' => 'Gibson Firebird',
            'GibsonLesPaul' => 'Gibson Les Paul',
            'GibsonSG' => 'Gibson SG',
            'GirlVoice' => 'Girl Voice',
            'Gittern' => 'Gittern',
            'Gizmo' => 'Gizmo',
            'GlassHarmonica' => 'Glass Harmonica',
            'GlassHarp' => 'Glass Harp',
            'Glockenspiel' => 'Glockenspiel',
            'Gong' => 'Gong',
            'GrandPiano' => 'Grand Piano',
            'GreatBassRecorder' => 'Great Bass Recorder',
            'GrooveBox' => 'Groove Box',
            'GroupBackgroundVocalists' => 'Group Background Vocalists',
            'Guacharaca' => 'Guacharaca',
            'Guache' => 'Guache',
            'Guanzi' => 'Guanzi',
            'Guira' => 'Guira',
            'Guiro' => 'Guiro',
            'Guitar' => 'Guitar',
            'Guitarron' => 'Guitarron',
            'GuitarSynth' => 'Guitar Synth',
            'Guqin' => 'Guqin',
            'Gusli' => 'Gusli',
            'Guzheng' => 'Guzheng',
            'Gyaling' => 'Gyaling',
            'HammeredDulcimer' => 'Hammered Dulcimer',
            'HammondB3' => 'Hammond B3',
            'HammondC3' => 'Hammond C3',
            'HammondOrgan' => 'Hammond Organ',
            'HandBells' => 'Hand Bells',
            'HandChimes' => 'Hand Chimes',
            'HandClaps' => 'Hand Claps',
            'HardangerFiddle' => 'Hardanger Fiddle',
            'Harmonica' => 'Harmonica',
            'Harmonium' => 'Harmonium',
            'Harpsichord' => 'Harpsichord',
            'HawaiianLapSteelGuitar' => 'Hawaiian Lap Steel Guitar',
            'Heckelphone' => 'Heckelphone',
            'Helicon' => 'Helicon',
            'HeraldTrumpet' => 'Herald Trumpet',
            'HighlandPipes' => 'Highland Pipes',
            'HighVocalRegister' => 'High Vocal Register',
            'HiHatCymbal' => 'Hi Hat Cymbal',
            'HofnerBass' => 'Hofner Bass',
            'HohnerGuitaret' => 'Hohner Guitaret',
            'HotFountainPen' => 'Hot Fountain Pen',
            'Huapanguera' => 'Huapanguera',
            'HurdyGurdy' => 'Hurdy Gurdy',
            'InfiniteGuitar' => 'Infinite Guitar',
            'IrishBouzouki' => 'Irish Bouzouki',
            'IrishLowWhistle' => 'Irish Low Whistle',
            'Jagdhorn' => 'Jagdhorn',
            'Jakhay' => 'Jakhay',
            'JamBlock' => 'Jam Block',
            'JaranaJarocha' => 'Jarana Jarocha',
            'Jawbone' => 'Jawbone',
            'Jawharp' => 'Jawharp',
            'JazzBand' => 'Jazz Band',
            'JewsHarp' => 'Jews Harp',
            'Jinghu' => 'Jinghu',
            'Jug' => 'Jug',
            'Kacapi' => 'Kacapi',
            'Kalimba' => 'Kalimba',
            'Kanjira' => 'Kanjira',
            'Kantele' => 'Kantele',
            'Kanun' => 'Kanun',
            'Katsa' => 'Katsa',
            'Kaval' => 'Kaval',
            'Kazoo' => 'Kazoo',
            'Kemenche' => 'Kemenche',
            'Kendang' => 'Kendang',
            'Keyboard' => 'Keyboard',
            'KeyboardBass' => 'Keyboard Bass',
            'KeyboardController' => 'Keyboard Controller',
            'KeyedTrumpet' => 'Keyed Trumpet',
            'Khamak' => 'Khamak',
            'Khartal' => 'Khartal',
            'Khene' => 'Khene',
            'Khim' => 'Khim',
            'Khlui' => 'Khlui',
            'Khol' => 'Khol',
            'KhongWongLek' => 'Khong Wong Lek',
            'KhongWongYai' => 'Khong Wong Yai',
            'KickDrum' => 'Kick Drum',
            'Knuckles' => 'Knuckles',
            'Kokin' => 'Kokin',
            'Kora' => 'Kora',
            'KorgMicrokorg' => 'Korg Microkorg',
            'KorgMS10' => 'Korg MS-10',
            'KorgTrident' => 'Korg Trident',
            'Koto' => 'Koto',
            'Kugo' => 'Kugo',
            'LakotaLoveFlute' => 'Lakota Love Flute',
            'Langeleik' => 'Langeleik',
            'Laouto' => 'Laouto',
            'LapSteelGuitar' => 'Lap Steel Guitar',
            'LatinGroup' => 'Latin Group',
            'LatinPercussion' => 'Latin Percussion',
            'Launeddas' => 'Launeddas',
            'Leona' => 'Leona',
            'Lirone' => 'Lirone',
            'Lithophone' => 'Lithophone',
            'Lokole' => 'Lokole',
            'Looping' => 'Looping',
            'LowreyOrgan' => 'Lowrey Organ',
            'LowVocalRegister' => 'Low Vocal Register',
            'Lur' => 'Lur',
            'Lute' => 'Lute',
            'Lutheal' => 'Lutheal',
            'LyraViol' => 'Lyra Viol',
            'Lyre' => 'Lyre',
            'Lyricon' => 'Lyricon',
            'Madal' => 'Madal',
            'MagneticTapeTreatments' => 'Magnetic Tape Treatments',
            'MaleBackgroundVocalist' => 'Male Background Vocalist',
            'MaleChoir' => 'Male Choir',
            'MaleVoice' => 'Male Voice',
            'Mandocello' => 'Mandocello',
            'Mandola' => 'Mandola',
            'Mandolele' => 'Mandolele',
            'Mandolin' => 'Mandolin',
            'Mandolino' => 'Mandolino',
            'Mandore' => 'Mandore',
            'Manjira' => 'Manjira',
            'Manzello' => 'Manzello',
            'Maracas' => 'Maracas',
            'MarchingBand' => 'Marching Band',
            'Marimba' => 'Marimba',
            'Marimbaphone' => 'Marimbaphone',
            'Marimbula' => 'Marimbula',
            'Marxophone' => 'Marxophone',
            'Mazhar' => 'Mazhar',
            'MechanicalInstrument' => 'Mechanical Instrument',
            'MedievalFiddle' => 'Medieval Fiddle',
            'MedievalHarp' => 'Medieval Harp',
            'MediumVocalRegister' => 'Medium Vocal Register',
            'Mellophone' => 'Mellophone',
            'Mellotron' => 'Mellotron',
            'Melodian' => 'Melodian',
            'Melodica' => 'Melodica',
            'MetalCans' => 'Metal Cans',
            'Metallophone' => 'Metallophone',
            'MezzoSopranoVoice' => 'Mezzo Soprano Voice',
            'Mijwiz' => 'Mijwiz',
            'MilitaryBand' => 'Military Band',
            'MiniatureKhene' => 'Miniature Khene',
            'MixedBackgroundVocalist' => 'Mixed Background Vocalist',
            'MixedChoir' => 'Mixed Choir',
            'MixedPercussion' => 'Mixed Percussion',
            'MixedVoice' => 'Mixed Voice',
            'Mizmar' => 'Mizmar',
            'MohanVeena' => 'Mohan Veena',
            'Moog' => 'Moog',
            'MoogGuitar' => 'Moog Guitar',
            'MoogMinimoog' => 'Moog Minimoog',
            'MoogPolymoog' => 'Moog Polymoog',
            'MoogRogue' => 'Moog Rogue',
            'MoogVoyager' => 'Moog Voyager',
            'MouthOrgan' => 'Mouth Organ',
            'MouthPercussion' => 'Mouth Percussion',
            'Mridangam' => 'Mridangam',
            'Muharsing' => 'Muharsing',
            'Musette' => 'Musette',
            'MusicalBow' => 'Musical Bow',
            'MusicBox' => 'Music Box',
            'Naal' => 'Naal',
            'Nadaswaram' => 'Nadaswaram',
            'Nagara' => 'Nagara',
            'Nai' => 'Nai',
            'NaturalHorn' => 'Natural Horn',
            'NaturalTrumpet' => 'Natural Trumpet',
            'NeutralVoice' => 'Neutral Voice',
            'NeyFlute' => 'Ney Flute',
            'Ngoni' => 'Ngoni',
            'Njarka' => 'Njarka',
            'Nonet' => 'Nonet',
            'Novachord' => 'Novachord',
            'NovationBassStation' => 'Novation Bass Station',
            'Nyatiti' => 'Nyatiti',
            'Nyckelharpa' => 'Nyckelharpa',
            'NylonStringGuitar' => 'Nylon String Guitar',
            'Oberheim' => 'Oberheim',
            'Oboe' => 'Oboe',
            'OboeDaCaccia' => 'Oboe DaCaccia',
            'OboeDAmore' => 'OboeDAmore',
            'OboromDrum' => 'Oborom Drum',
            'Ocarina' => 'Ocarina',
            'Octet' => 'Octet',
            'Octoban' => 'Octoban',
            'Omnichord' => 'Omnichord',
            'OndesMartenot' => 'Ondes Martenot',
            'Ophicleide' => 'Ophicleide',
            'Optigan' => 'Optigan',
            'Orchestra' => 'Orchestra',
            'OrchestralHit' => 'Orchestral Hit',
            'OrchestralPercussion' => 'Orchestral Percussion',
            'Organistrum' => 'Organistrum',
            'Orpharion' => 'Orpharion',
            'Oud' => 'Oud',
            'PaddleDrums' => 'Paddle Drums',
            'Paixiao' => 'Paixiao',
            'Palmas' => 'Palmas',
            'Pandeiro' => 'Pandeiro',
            'Pandura' => 'Pandura',
            'PanFlute' => 'Pan Flute',
            'ParaguayanHarp' => 'Paraguayan Harp',
            'Pedabro' => 'Pedabro',
            'PedalSteelGuitar' => 'Pedal Steel Guitar',
            'PercussionInstrument' => 'Percussion Instrument',
            'PercussionSection' => 'Percussion Section',
            'Phin' => 'Phin',
            'Phonofiddle' => 'Phonofiddle',
            'Pi' => 'Pi',
            'Pianet' => 'Pianet',
            'PianoAccordion' => 'Piano Accordion',
            'PianoHarp' => 'Piano Harp',
            'Pianola' => 'Pianola',
            'PiccoloBass' => 'Piccolo Bass',
            'PiccoloClarinet' => 'Piccolo Clarinet',
            'PiccoloFlute' => 'Piccolo Flute',
            'PiccoloTrumpet' => 'Piccolo Trumpet',
            'PiccoloVoice' => 'Piccolo Voice',
            'Pinkillu' => 'Pinkillu',
            'Pipa' => 'Pipa',
            'Pipe' => 'Pipe',
            'PipeAndDrumGroup' => 'Pipe And Drum Group',
            'PipeOrgan' => 'Pipe Organ',
            'PitchedPercussionInstrument' => 'Pitched Percussion Instrument',
            'PluckedDulcimer' => 'Plucked Dulcimer',
            'PocketTrumpet' => 'Pocket Trumpet',
            'PoliceWhistle' => 'Police Whistle',
            'PongLang' => 'Pong Lang',
            'PopBand' => 'Pop Band',
            'PortugueseGuitar' => 'Portuguese Guitar',
            'PositiveOrgan' => 'Positive Organ',
            'PostHorn' => 'Post Horn',
            'PotsAndPans' => 'Pots And Pans',
            'PreparedPiano' => 'Prepared Piano',
            'Psaltery' => 'Psaltery',
            'Pungi' => 'Pungi',
            'Qarkabeb' => 'Qarkabeb',
            'Quartet' => 'Quartet',
            'Quena' => 'Quena',
            'Quenacho' => 'Quenacho',
            'Quintet' => 'Quintet',
            'Rabel' => 'Rabel',
            'Rackett' => 'Rackett',
            'Rainstick' => 'Rainstick',
            'Ranat' => 'Ranat',
            'Ratchet' => 'Ratchet',
            'Rattle' => 'Rattle',
            'Rauschpfeife' => 'Rauschpfeife',
            'Rebab' => 'Rebab',
            'Rebec' => 'Rebec',
            'Recorder' => 'Recorder',
            'RecoReco' => 'Reco Reco',
            'ReedInstrument' => 'Reed Instrument',
            'ReedOrgan' => 'Reed Organ',
            'Regal' => 'Regal',
            'RenaissanceGuitar' => 'Renaissance Guitar',
            'Repinique' => 'Repinique',
            'Rhodes' => 'Rhodes',
            'RhythmStick' => 'Rhythm Stick',
            'RideCymbal' => 'Ride Cymbal',
            'Riq' => 'Riq',
            'Rnga' => 'Rnga',
            'RockBand' => 'Rock Band',
            'RolandHandsonicHPD15' => 'Roland Handsonic HPD-15',
            'RolandJuno' => 'Roland Juno',
            'RolandJuno60' => 'Roland Juno-60',
            'RolandJupiter8' => 'Roland Jupiter-8',
            'RolandParaphonicStringsRS505' => 'Roland Paraphonic StringsRS-505',
            'RolandTB303' => 'RolandTB303',
            'RolandTR808' => 'RolandTR808',
            'RolandVDrums' => 'RolandVDrums',
            'Rolmo' => 'Rolmo',
            'RomanticGuitar' => 'Romantic Guitar',
            'Rondador' => 'Rondador',
            'Rototoms' => 'Rototoms',
            'Sabar' => 'Sabar',
            'Sackbut' => 'Sackbut',
            'SampledDrumMachine' => 'Sampled Drum Machine',
            'SampledKeyboard' => 'Sampled Keyboard',
            'Sampler' => 'Sampler',
            'SampleSequencer' => 'Sample Sequencer',
            'SandBlocks' => 'Sand Blocks',
            'Santoor' => 'Santoor',
            'Sarangi' => 'Sarangi',
            'Sarod' => 'Sarod',
            'Sarrusophone' => 'Sarrusophone',
            'Saung' => 'Saung',
            'Saw' => 'Saw',
            'SawDuang' => 'Saw Duang',
            'Saxello' => 'Saxello',
            'Saxophone' => 'Saxophone',
            'Scratcher' => 'Scratcher',
            'Septet' => 'Septet',
            'SequentialCircuitsProphet5' => 'Sequential Circuits Prophet 5',
            'Serpent' => 'Serpent',
            'Sextet' => 'Sextet',
            'Shaker' => 'Shaker',
            'Shakuhachi' => 'Shakuhachi',
            'Shamisen' => 'Shamisen',
            'Shawm' => 'Shawm',
            'Shekere' => 'Shekere',
            'Shelltone' => 'Shelltone',
            'Shenai' => 'Shenai',
            'Sheng' => 'Sheng',
            'Sho' => 'Sho',
            'Shofar' => 'Shofar',
            'ShrutiBox' => 'Shruti Box',
            'ShviWhistle' => 'Shvi Whistle',
            'Siku' => 'Siku',
            'Simsimiyya' => 'Simsimiyya',
            'SingingBowls' => 'Singing Bowls',
            'Sintir' => 'Sintir',
            'Siren' => 'Siren',
            'Sistrum' => 'Sistrum',
            'Sitar' => 'Sitar',
            'Slapstick' => 'Slapstick',
            'SleighBells' => 'Sleigh Bells',
            'SlideGuitar' => 'Slide Guitar',
            'SlideSaxophone' => 'Slide Saxophone',
            'SlideTrumpet' => 'Slide Trumpet',
            'SlideWhistle' => 'Slide Whistle',
            'SnareDrum' => 'Snare Drum',
            'SnareDrum(Marching)' => 'Snare Drum (Marching)',
            'SoftwareSynthesizer' => 'Software Synthesizer',
            'SopraninoRecorder' => 'Sopranino Recorder',
            'SopraninoSaxophone' => 'Sopranino Saxophone',
            'SopraninoVoice' => 'Sopranino Voice',
            'SopranoClarinet' => 'Soprano Clarinet',
            'SopranoCornet' => 'Soprano Cornet',
            'SopranoCrumhorn' => 'Soprano Crumhorn',
            'SopranoDomra' => 'Soprano Domra',
            'SopranoDulcian' => 'Soprano Dulcian',
            'SopranoRecorder' => 'Soprano Recorder',
            'SopranoSaxophone' => 'Soprano Saxophone',
            'SopranoShawm' => 'Soprano Shawm',
            'SopranoTrumpet' => 'Soprano Trumpet',
            'SopranoVoice' => 'Soprano Voice',
            'Sordun' => 'Sordun',
            'SoundEffects' => 'Sound Effects',
            'Sousaphone' => 'Sousaphone',
            'SpectrasonicsOmnisphere' => 'Spectrasonics Omnisphere',
            'Spinet' => 'Spinet',
            'SplashCymbal' => 'Splash Cymbal',
            'Spoons' => 'Spoons',
            'SpringDrum' => 'Spring Drum',
            'SquarePiano' => 'Square Piano',
            'StarInstrumentsSynare' => 'Star Instruments Synare',
            'SteelDrums' => 'Steel Drums',
            'Sticks' => 'Sticks',
            'StringedKeyboardInstrument' => 'Stringed Keyboard Instrument',
            'StringInstrument' => 'String Instrument',
            'StringMachine' => 'String Machine',
            'StringSection' => 'String Section',
            'Stritch' => 'Stritch',
            'StrohlViolin' => 'Strohl Violin',
            'Strumstick' => 'Strumstick',
            'Stylophone' => 'Stylophone',
            'Suling' => 'Suling',
            'Suona' => 'Suona',
            'Surbahar' => 'Surbahar',
            'Surdo' => 'Surdo',
            'Swarmandal' => 'Swarmandal',
            'Synclavier' => 'Synclavier',
            'Syncussion' => 'Syncussion',
            'Syndrums' => 'Syndrums',
            'SynthBass' => 'Synth Bass',
            'SynthBrass' => 'Synth Brass',
            'SynthChoir' => 'Synth Choir',
            'Synthesizer' => 'Synthesizer',
            'SynthPad' => 'Synth Pad',
            'SynthSteelDrums' => 'Synth Steel Drums',
            'SynthStrings' => 'Synth Strings',
            'Taal' => 'Taal',
            'Taarija' => 'Taarija',
            'Tabla' => 'Tabla',
            'Tabor' => 'Tabor',
            'TackPiano' => 'Tack Piano',
            'TacomaPaposeAcousticGuitar' => 'Tacoma Papose Acoustic Guitar',
            'Taiko' => 'Taiko',
            'TalkingDrum' => 'Talking Drum',
            'Tambora' => 'Tambora',
            'Tamborim' => 'Tamborim',
            'Tambourine' => 'Tambourine',
            'Tambura' => 'Tambura',
            'Tanbour' => 'Tanbour',
            'Tanpura' => 'Tanpura',
            'TaongaPuoro' => 'Taonga Puoro',
            'Tar(Percussion)' => 'Tar (Percussion)',
            'Tar(String)' => 'Tar (String)',
            'Tarka' => 'Tarka',
            'Tarogato' => 'Tarogato',
            'Tarol' => 'Tarol',
            'TempleBell' => 'Temple Bell',
            'TempleBlocks' => 'Temple Blocks',
            'Tenor' => 'Tenor',
            'TenorBanjo' => 'Tenor Banjo',
            'TenorClarinet' => 'Tenor Clarinet',
            'TenorCrumhorn' => 'Tenor Crumhorn',
            'TenorDrum' => 'Tenor Drum',
            'TenorDulcian' => 'Tenor Dulcian',
            'TenorFlute' => 'Tenor Flute',
            'TenorGuitar' => 'Tenor Guitar',
            'TenorHorn' => 'Tenor Horn',
            'TenorRebec' => 'Tenor Rebec',
            'TenorRecorder' => 'Tenor Recorder',
            'TenorSackbut' => 'Tenor Sackbut',
            'TenorSaxophone' => 'Tenor Saxophone',
            'TenorShawm' => 'Tenor Shawm',
            'TenorTuba' => 'Tenor Tuba',
            'TenorViol' => 'Tenor Viol',
            'Thavil' => 'Thavil',
            'Theorbo' => 'Theorbo',
            'Theremin' => 'Theremin',
            'ThunderSheet' => 'Thunder Sheet',
            'TibetanBells' => 'Tibetan Bells',
            'TibetanBowls' => 'Tibetan Bowls',
            'Timbales' => 'Timbales',
            'Timbau' => 'Timbau',
            'Timpani' => 'Timpani',
            'Timple' => 'Timple',
            'Tingsha' => 'Tingsha',
            'TinWhistle' => 'Tin Whistle',
            'Tiple' => 'Tiple',
            'TogamanGuitarViol' => 'Togaman Guitar Viol',
            'Tompak' => 'Tompak',
            'Toms' => 'Toms',
            'TongueDrum' => 'Tongue Drum',
            'TouchGuitar' => 'Touch Guitar',
            'ToyAccordion' => 'Toy Accordion',
            'ToyInstrument' => 'Toy Instrument',
            'ToyPiano' => 'Toy Piano',
            'TraversoFlute' => 'Traverso Flute',
            'Treatments' => 'Treatments',
            'TrebleRebec' => 'Treble Rebec',
            'TrebleViol' => 'Treble Viol',
            'Tres' => 'Tres',
            'Triangle' => 'Triangle',
            'Trio' => 'Trio',
            'TrombaMarina' => 'Tromba Marina',
            'Trombone' => 'Trombone',
            'Trumpet' => 'Trumpet',
            'Tuba' => 'Tuba',
            'TubularBells' => 'Tubular Bells',
            'Tumbi' => 'Tumbi',
            'Turntable' => 'Turntable',
            'Tusselfloyte' => 'Tusselfloyte',
            'Txalaparta' => 'Txalaparta',
            'Tzouras' => 'Tzouras',
            'Udu' => 'Udu',
            'UilleanPipes' => 'Uillean Pipes',
            'Ukulele' => 'Ukulele',
            'UliUli' => 'Uli Uli',
            'Unknown' => 'Unknown',
            'UnpitchedPercussionInstrument' => 'Unpitched Percussion Instrument',
            'UprightBass' => 'Upright Bass',
            'UprightPiano' => 'Upright Piano',
            'Urumee' => 'Urumee',
            'UserDefined' => 'User Defined',
            'VakoOrchestron' => 'Vako Orchestron',
            'Valiha' => 'Valiha',
            'ValveTrombone' => 'Valve Trombone',
            'Veena' => 'Veena',
            'VenezualanHarp' => 'Venezualan Harp',
            'VeracruzHarp' => 'Veracruz Harp',
            'Vibraphone' => 'Vibraphone',
            'Vibraslap' => 'Vibraslap',
            'VichitraVeena' => 'Vichitra Veena',
            'Vielle' => 'Vielle',
            'Vihuela' => 'Vihuela',
            'Viol' => 'Viol',
            'Viola' => 'Viola',
            'ViolaCaipira' => 'Viola Caipira',
            'ViolaDaBraccio' => 'Viola DaBraccio',
            'ViolaDaGamba' => 'Viola DaGamba',
            'ViolaDAmore' => 'ViolaDAmore',
            'ViolaPomposa' => 'Viola Pomposa',
            'Violin' => 'Violin',
            'ViolinoPiccolo' => 'Violino Piccolo',
            'Virginals' => 'Virginals',
            'ViTar' => 'Vi Tar',
            'VocalAccompaniment' => 'Vocal Accompaniment',
            'VocalEnsemble' => 'Vocal Ensemble',
            'Voice' => 'Voice',
            'VoxContinental' => 'Vox Continental',
            'WagnerTuba' => 'Wagner Tuba',
            'Washboard' => 'Washboard',
            'WashtubBass' => 'Washtub Bass',
            'Waterphone' => 'Waterphone',
            'WelshTripleHarp' => 'Welsh Triple Harp',
            'WillowFlute' => 'Willow Flute',
            'WindChimes' => 'Wind Chimes',
            'WindInstrument' => 'Wind Instrument',
            'WindMachine' => 'Wind Machine',
            'WindSection' => 'Wind Section',
            'WineGlasses' => 'Wine Glasses',
            'WireStrungHarp' => 'Wire Strung Harp',
            'WobbleBoard' => 'Wobble Board',
            'WoodBlock' => 'Wood Block',
            'WoodFlute' => 'Wood Flute',
            'WoodTrumpet' => 'Wood Trumpet',
            'WoodwindSection' => 'Woodwind Section',
            'Wot' => 'Wot',
            'Wurlitzer200A' => 'Wurlitzer 200A',
            'WurlitzerElectricPiano' => 'Wurlitzer Electric Piano',
            'WurlitzerOrgan' => 'Wurlitzer Organ',
            'Xalam' => 'Xalam',
            'Xaphoon' => 'Xaphoon',
            'Xiao' => 'Xiao',
            'Xun' => 'Xun',
            'Xylophone' => 'Xylophone',
            'Xylorimba' => 'Xylorimba',
            'YamahaCP80' => 'Yamaha CP-80',
            'YamahaCS80' => 'Yamaha CS-80',
            'YamahaQY70' => 'Yamaha QY70',
            'Yangqin' => 'Yangqin',
            'YayliTambur' => 'Yayli Tambur',
            'Yokin' => 'Yokin',
            'Yueqin' => 'Yueqin',
            'Zampona' => 'Zampona',
            'Zerbaghali' => 'Zerbaghali',
            'Zeze' => 'Zeze',
            'Zhonghu' => 'Zhonghu',
            'Zither' => 'Zither',
            'Zourna' => 'Zourna',
            'Zummara' => 'Zummara',
            'Zurla' => 'Zurla',
            'Zurna' => 'Zurna',
            'ZydecoRubboard' => 'Zydeco Rubboard',
            '12-StringElectricGuitar' => '12-String Electric Guitar',
            '12-StringGuitar' => '12-String Guitar',
            '5-StringBanjo' => '5-String Banjo',
        ];

        foreach ($instruments as $ddex_key => $name) {
            Instrument::create([
                'name'         => $name,
                'ddex_key'     => $ddex_key,
                'user_defined' => $ddex_key === 'UserDefined',
            ]);
        }
    }
}
