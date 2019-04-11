<?php

use App\Models\CreditRole;
use Illuminate\Database\Seeder;

class CreditRolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            'ResourceContributorRole' => [
                'Accompanyist'              => 'Accompanyist',
                'Actor'                     => 'Actor',
                'Additional Performer'      => 'AdditionalPerformer',
                'Anchor Person'             => 'AnchorPerson',
                'Announcer'                 => 'Announcer',
                'Artist'                    => 'Artist',
                'Associated Performer'      => 'AssociatedPerformer',
                'Background Vocalist'       => 'BackgroundVocalist',
                'Band Leader'               => 'BandLeader',
                'Causeur'                   => 'Causeur',
                'Choir Member'              => 'ChoirMember',
                'Choir'                     => 'Choir',
                'Chorus Master'             => 'ChorusMaster',
                'Circus Artist'             => 'CircusArtist',
                'ClubDJ'                    => 'ClubDJ',
                'Comedian'                  => 'Comedian',
                'Commentator'               => 'Commentator',
                'Conductor'                 => 'Conductor',
                'Dancer'                    => 'Dancer',
                'Ensemble'                  => 'Ensemble',
                'Featured Artist'           => 'FeaturedArtist',
                'Group Member'              => 'GroupMember',
                'Interviewed Guest'         => 'InterviewedGuest',
                'Interviewer'               => 'Interviewer',
                'Key Character'             => 'KeyCharacter',
                'Key Talent'                => 'KeyTalent',
                'Lead Performer'            => 'LeadPerformer',
                'Main Artist'               => 'MainArtist',
                'Music Group'               => 'MusicGroup',
                'Musician'                  => 'Musician',
                'Narrator'                  => 'Narrator',
                'News Reader'               => 'NewsReader',
                'Orchestra Member'          => 'OrchestraMember',
                'Orchestra'                 => 'Orchestra',
                'Primary Musician'          => 'PrimaryMusician',
                'Puppeteer'                 => 'Puppeteer',
                'Sign Language Interpreter' => 'SignLanguageInterpreter',
                'Soloist'                   => 'Soloist',
                'Speaker'                   => 'Speaker',
                'Story Teller'              => 'StoryTeller',
                'Studio Musician'           => 'StudioMusician',
                'Stunts'                    => 'Stunts',
                'Supporting Actor'          => 'SupportingActor',
                'Unknown'                   => 'Unknown',
                'User Defined'              => 'UserDefined',
                'Voice Actor'               => 'VoiceActor',
            ],
            'CreativeContributorRole' => [
                'Adapter'                         => 'Adapter',
                'Architect'                       => 'Architect',
                'Arranger'                        => 'Arranger',
                'Author In Quotations'            => 'AuthorInQuotations',
                'Author Of Afterword'             => 'AuthorOfAfterword',
                'Author'                          => 'Author',
                'Compiler'                        => 'Compiler',
                'Composer Lyricist'               => 'ComposerLyricist',
                'Composer'                        => 'Composer',
                'Conceptor'                       => 'Conceptor',
                'Creator'                         => 'Creator',
                'Dialogue Author'                 => 'DialogueAuthor',
                'Dissertant'                      => 'Dissertant',
                'Engraver'                        => 'Engraver',
                'Etcher'                          => 'Etcher',
                'Journalist'                      => 'Journalist',
                'Landscape Architect'             => 'LandscapeArchitect',
                'Librettist'                      => 'Librettist',
                'Lithographer'                    => 'Lithographer',
                'Lyricist'                        => 'Lyricist',
                'Metal Engraver'                  => 'MetalEngraver',
                'Non Lyric Author'                => 'NonLyricAuthor',
                'Plate Maker'                     => 'PlateMaker',
                'Playwright'                      => 'Playwright',
                'Reporter'                        => 'Reporter',
                'Reviewer'                        => 'Reviewer',
                'Rubricator'                      => 'Rubricator',
                'Screenplay Author'               => 'ScreenplayAuthor',
                'Sculptor'                        => 'Sculptor',
                'Sub Arranger'                    => 'SubArranger',
                'Sub Lyricist'                    => 'SubLyricist',
                'Translator'                      => 'Translator',
                'Wood Engraver'                   => 'WoodEngraver',
                'Woodcutter'                      => 'Woodcutter',
                'Writer Of Accompanying Material' => 'WriterOfAccompanyingMaterial',
            ],
            'BusinessContributorRole' => [
                'Book Publisher'        => 'BookPublisher',
                'Copyright Claimant'    => 'CopyrightClaimant',
                'Copyright Holder'      => 'CopyrightHolder',
                'Music Publisher'       => 'MusicPublisher',
                'Newspaper Publisher'   => 'NewspaperPublisher',
                'Original Publisher'    => 'OriginalPublisher',
                'Periodical Publisher'  => 'PeriodicalPublisher',
                'Sub Publisher'         => 'SubPublisher',
                'Substituted Publisher' => 'SubstitutedPublisher',
                'Unknown'               => 'Unknown',
                'User Defined'          => 'UserDefined',
            ],
            'NewStudioRole' => [
                'A And R Coordinator'                    => 'AAndRCoordinator',
                'Additional Engineer'                    => 'AdditionalEngineer',
                'Animal Trainer'                         => 'AnimalTrainer',
                'Animator'                               => 'Animator',
                'Annotator'                              => 'Annotator',
                'Armourer'                               => 'Armourer',
                'Art Director'                           => 'ArtDirector',
                'Artist Background Vocal Engineer'       => 'ArtistBackgroundVocalEngineer',
                'Artist Vocal Engineer'                  => 'ArtistVocalEngineer',
                'Artist Vocal Second Engineer'           => 'ArtistVocalSecondEngineer',
                'Assistant Camera Operator'              => 'AssistantCameraOperator',
                'Assistant Chief Lighting Technician'    => 'AssistantChiefLightingTechnician',
                'Assistant Director'                     => 'AssistantDirector',
                'Assistant Producer'                     => 'AssistantProducer',
                'Assistant Visual Editor'                => 'AssistantVisualEditor',
                'Aural Trainer'                          => 'AuralTrainer',
                'Binder'                                 => 'Binder',
                'Binding Designer'                       => 'BindingDesigner',
                'Book Designer'                          => 'BookDesigner',
                'Book Producer'                          => 'BookProducer',
                'Bookjack Designer'                      => 'BookjackDesigner',
                'Bookplate Designer'                     => 'BookplateDesigner',
                'Broadcast Assistant'                    => 'BroadcastAssistant',
                'Broadcast Journalist'                   => 'BroadcastJournalist',
                'Camera Operator'                        => 'CameraOperator',
                'Carpenter'                              => 'Carpenter',
                'Casting Director'                       => 'CastingDirector',
                'Censor'                                 => 'Censor',
                'Chief Lighting Technician'              => 'ChiefLightingTechnician',
                'Choreographer'                          => 'Choreographer',
                'Clapper Loader'                         => 'ClapperLoader',
                'Co Executive Producer'                  => 'CoExecutiveProducer',
                'Co Producer'                            => 'CoProducer',
                'Commissioning Broadcaster'              => 'CommissioningBroadcaster',
                'Compilation Producer'                   => 'CompilationProducer',
                'Consultant'                             => 'Consultant',
                'Continuity Checker'                     => 'ContinuityChecker',
                'Contractor'                             => 'Contractor',
                'Correspondent'                          => 'Correspondent',
                'Costume Designer'                       => 'CostumeDesigner',
                'Cover Designer'                         => 'CoverDesigner',
                'Designer'                               => 'Designer',
                'Dialogue Coach'                         => 'DialogueCoach',
                'Digital Audio Workstation Engineer'     => 'DigitalAudioWorkstationEngineer',
                'Digital Editing Engineer'               => 'DigitalEditingEngineer',
                'Digital Editing Second Engineer'        => 'DigitalEditingSecondEngineer',
                'Direct Stream Digital Engineer'         => 'DirectStreamDigitalEngineer',
                'Director'                               => 'Director',
                'Distribution Company'                   => 'DistributionCompany',
                'Dresser'                                => 'Dresser',
                'Dubber'                                 => 'Dubber',
                'Editor In Chief'                        => 'EditorInChief',
                'Editor Of The Day'                      => 'EditorOfTheDay',
                'Editor'                                 => 'Editor',
                'Encoder'                                => 'Encoder',
                'Engineer'                               => 'Engineer',
                'Executive Producer'                     => 'ExecutiveProducer',
                'Expert'                                 => 'Expert',
                'Fight Director'                         => 'FightDirector',
                'Film Director'                          => 'FilmDirector',
                'Film Distributor'                       => 'FilmDistributor',
                'Film Editor'                            => 'FilmEditor',
                'Film Producer'                          => 'FilmProducer',
                'Film Sound Engineer'                    => 'FilmSoundEngineer',
                'Floor Manager'                          => 'FloorManager',
                'Focus Puller'                           => 'FocusPuller',
                'Foley Artist'                           => 'FoleyArtist',
                'Foley Editor'                           => 'FoleyEditor',
                'Foley Mixer'                            => 'FoleyMixer',
                'Graphic Assistant'                      => 'GraphicAssistant',
                'Graphic Designer'                       => 'GraphicDesigner',
                'Greensman'                              => 'Greensman',
                'Grip'                                   => 'Grip',
                'Hairdresser'                            => 'Hairdresser',
                'Initial Producer'                       => 'InitialProducer',
                'Key Grip'                               => 'KeyGrip',
                'Leadman'                                => 'Leadman',
                'Lighting Director'                      => 'LightingDirector',
                'Lighting Technician'                    => 'LightingTechnician',
                'Location Manager'                       => 'LocationManager',
                'Make Up Artist'                         => 'MakeUpArtist',
                'Manufacturer'                           => 'Manufacturer',
                'Mastering Engineer'                     => 'MasteringEngineer',
                'Mastering Second Engineer'              => 'MasteringSecondEngineer',
                'Matte Artist'                           => 'MatteArtist',
                'Mixing Engineer'                        => 'MixingEngineer',
                'Mixing Second Engineer'                 => 'MixingSecondEngineer',
                'Music Director'                         => 'MusicDirector',
                'Musician'                               => 'Musician',
                'News Producer'                          => 'NewsProducer',
                'Overdub Engineer'                       => 'OverdubEngineer',
                'Overdub Second Engineer'                => 'OverdubSecondEngineer',
                'Photography Director'                   => 'PhotographyDirector',
                'Post Producer'                          => 'PostProducer',
                'Pre Production Engineer'                => 'PreProductionEngineer',
                'Pre Production'                         => 'PreProduction',
                'Production Company'                     => 'ProductionCompany',
                'Production Department'                  => 'ProductionDepartment',
                'Production Manager'                     => 'ProductionManager',
                'Production Secretary'                   => 'ProductionSecretary',
                'Program Producer'                       => 'ProgramProducer',
                'Programming Engineer'                   => 'ProgrammingEngineer',
                'Property Manager'                       => 'PropertyManager',
                'Publishing Director'                    => 'PublishingDirector',
                'Pyrotechnician'                         => 'Pyrotechnician',
                'Recording Engineer'                     => 'RecordingEngineer',
                'Recording Second Engineer'              => 'RecordingSecondEngineer',
                'Redactor'                               => 'Redactor',
                'Reissue Producer'                       => 'ReissueProducer',
                'Remixing Engineer'                      => 'RemixingEngineer',
                'Remixing Second Engineer'               => 'RemixingSecondEngineer',
                'Repetiteur'                             => 'Repetiteur',
                'Research Team Head'                     => 'ResearchTeamHead',
                'Research Team Member'                   => 'ResearchTeamMember',
                'Researcher'                             => 'Researcher',
                'Restager'                               => 'Restager',
                'Rigger'                                 => 'Rigger',
                'Rights Controller On Product'           => 'RightsControllerOnProduct',
                'Runner'                                 => 'Runner',
                'Scenic Operative'                       => 'ScenicOperative',
                'Scientific Advisor'                     => 'ScientificAdvisor',
                'Script Supervisor'                      => 'ScriptSupervisor',
                'Second Assistant Camera Operator'       => 'SecondAssistantCameraOperator',
                'Second Assistant Director'              => 'SecondAssistantDirector',
                'Second Engineer'                        => 'SecondEngineer',
                'Second Unit Director'                   => 'SecondUnitDirector',
                'Series Producer'                        => 'SeriesProducer',
                'Set Designer'                           => 'SetDesigner',
                'Set Dresser'                            => 'SetDresser',
                'Sound Designer'                         => 'SoundDesigner',
                'Sound Mixer'                            => 'SoundMixer',
                'Sound Recordist'                        => 'SoundRecordist',
                'Special Effects Technician'             => 'SpecialEffectsTechnician',
                'Sponsor'                                => 'Sponsor',
                'Stage Director'                         => 'StageDirector',
                'String Engineer'                        => 'StringEngineer',
                'String Producer'                        => 'StringProducer',
                'Studio Conductor'                       => 'StudioConductor',
                'Studio Personnel'                       => 'StudioPersonnel',
                'Studio Producer'                        => 'StudioProducer',
                'Subtitles Editor'                       => 'SubtitlesEditor',
                'Subtitles Translator'                   => 'SubtitlesTranslator',
                'Tape Operator'                          => 'TapeOperator',
                'Technical Director'                     => 'TechnicalDirector',
                'Tonmeister'                             => 'Tonmeister',
                'Tracking Engineer'                      => 'TrackingEngineer',
                'Tracking Second Engineer'               => 'TrackingSecondEngineer',
                'Transfers And Safeties Engineer'        => 'TransfersAndSafetiesEngineer',
                'Transfers And Safeties Second Engineer' => 'TransfersAndSafetiesSecondEngineer',
                'Transportation Manager'                 => 'TransportationManager',
                'Treatment/Program Proposal'             => 'Treatment/ProgramProposal',
                'User Defined'                           => 'UserDefined',
                'Video Producer'                         => 'VideoProducer',
                'Videographer'                           => 'Videographer',
                'Vision Mixer'                           => 'VisionMixer',
                'Visual Editor'                          => 'VisualEditor',
                'Visual Effects Technician'              => 'VisualEffectsTechnician',
                'Vocal Producer'                         => 'VocalProducer',
                'Wardrobe'                               => 'Wardrobe',
            ],
            'ArtistRole' => [
                'Art Copyist'              => 'ArtCopyist',
                'Calligrapher'             => 'Calligrapher',
                'Cartographer'             => 'Cartographer',
                'Cartoonist'               => 'Cartoonist',
                'Computer Graphic Creator' => 'ComputerGraphicCreator',
                'Computer Programmer'      => 'ComputerProgrammer',
                'Delineator'               => 'Delineator',
                'Draughtsman'              => 'Draughtsman',
                'Facsimilist'              => 'Facsimilist',
                'Graphic Artist'           => 'GraphicArtist',
                'Illustrator'              => 'Illustrator',
                'Music Copyist'            => 'MusicCopyist',
                'Not Specified'            => 'NotSpecified',
                'Painter'                  => 'Painter',
                'Photographer'             => 'Photographer',
                'Type Designer'            => 'TypeDesigner',
                'Unknown'                  => 'Unknown',
                'User Defined'             => 'UserDefined',
            ],
        ];

        foreach ($roles as $type => $r) {
            foreach ($r as $name => $ddex_key) {
                CreditRole::create([
                    'name'         => $name,
                    'type'         => $type,
                    'ddex_key'     => $ddex_key,
                    'user_defined' => $ddex_key === 'UserDefined'
                ]);
            }
        }
    }
}
