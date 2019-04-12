<?php

namespace App\Util\RIN;

use App\Contracts\Creditable;
use App\Models\Credit;
use App\Models\CreditRole;
use App\Models\Party;
use App\Models\Project;
use App\Models\Recording;
use App\Models\RecordingType;
use App\Models\Session;
use App\Models\SessionType;
use App\Models\Song;
use App\Models\SongType;
use App\Models\User;
use App\Models\Venue;
use App\Util\RIN\Utilities;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use SimpleXMLElement;

class Importer
{
    const PROJECT_ID_PREFIX = 'J-';
    const PARTY_ID_PREFIX = 'P-';
    const SESSION_ID_PREFIX = 'O-';
    const SONG_ID_PREFIX = 'W-';
    const RECORDING_ID_PREFIX = 'A-';

    private $fileId;
    private $project;
    private $parties;
    private $recordings;
    private $sessions;
    private $songs;

    private $projectOwner;
    private $masterProject;

    private $rinVersion = '10';

    /**
     * Import a RIN from an XML document object.
     *
     * @param  SimpleXMLElement $xml
     * @return Importer
     */
    public function fromXML(SimpleXMLElement $xml): Importer
    {
        $rinNamespace = array_get($xml->getNamespaces(), 'rin', null);

        if (is_null($rinNamespace)) {
            throw new \Exception('Missing RIN namespace on XML document.');
        }

        $this->rinVersion = $this->extractVersion($rinNamespace);

        if (!in_array($this->rinVersion, ['10', '11'])) {
            throw new \Exception('We only support importing RIN versions 1.0 and 1.1');
        }

        $this->fileId = $xml->FileHeader->FileId;

        $this->parties = $this->mapParties($xml->PartyList->children());
        $this->project = $this->mapProject($xml->ProjectList->Project);

        $this->recordings = $this->mapRecordings($xml->ResourceList->children());
        $this->sessions = $this->mapSessions($xml->SessionList->children());
        $this->songs = $this->mapSongs($xml->MusicalWorkList->children());

        return $this;
    }

    private function extractVersion(string $rinNamespace): string
    {
        $matches = [];
        preg_match('!\d+!', $rinNamespace, $matches);
        return current($matches);
    }

    /**
     * Actually run the import into the database
     *
     * @param bool $override
     */
    public function import(bool $override)
    {
        DB::beginTransaction();
        try {
            $parties = $this->importParties($this->parties, $override);
            $project = $this->importProject($this->project, $parties, $override);
            $songs = $this->importSongs($this->songs, $parties, $override);
            $sessions = $this->importSessions($this->sessions, $project, $parties, $override);
            $recordings = $this->importRecordings($this->recordings, $project, $songs, $parties, $sessions, $override);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            throw $e;
        }
    }

    /**
     * Set the current master project.
     *
     * @param App\Models\Project $project
     */
    public function setProject(Project $project): Importer
    {
        $this->masterProject = $project;
        $this->projectOwner = $this->masterProject->user;
        return $this;
    }

    /**
     * Import the project into the database.s
     *
     * @param  array        $project
     * @param  array        $parties
     * @param  bool|boolean $override
     * @return Project
     */
    private function importProject(array $project, array $parties,  bool $override = false): Project
    {
        $projectId = array_get($project, 'id', false);
        $project = array_except($project, ['id']);

        $projectModel = null;
        if ($override) {
            $projectModel = Project::where('id', $projectId)->userUpdatable(['user' => $this->projectOwner])->first();
        }

        if (!$projectModel) {
            $project['user_id'] = $this->projectOwner->getKey();
            $projectModel = new Project();
        }

        $projectModel->fill(array_except($project, ['credits']));
        $projectModel->save();

        $this->importCredits($projectModel, $project['credits'], $parties);

        return $projectModel;
    }

    private function importCredits(Creditable $model, SimpleXMLElement $credits, array $allParties): array
    {
        $creditIds = [];
        $creditReferenceKey = $model->getContributorReferenceKey();

        foreach($credits as $credit) {
            // TODO: Party requires user access.
            $contributionId = (int) str_replace(self::PARTY_ID_PREFIX, '', (string) $credit->{$creditReferenceKey});
            $contributionRole = (string) $credit->Role;

            if (array_key_exists($contributionId, $allParties)) {
                $contributionModel = array_get($allParties, $contributionId);
                $contributionId = $contributionModel->getKey();
            }

            $creditRoleTypes = $model->getContributorRoleTypes();
            $creditRoleId = null;
            $userDefinedValue = null;
            $creditRole = CreditRole::whereIn('type', $creditRoleTypes)->where('ddex_key', $contributionRole)->first();

            if (!$creditRole) {
                Log::debug(sprintf('Missing credit role for %s: %s', join(', ', $creditRoleTypes), $contributionRole));
                $creditRole = CreditRole::whereIn('type', $creditRoleTypes)->where('ddex_key', 'UserDefined')->first();
            }

            if ($creditRole) {
                $creditRoleId = $creditRole->getKey();

                if ((bool) $creditRole->user_defined) {
                    $creditRoleAttributes = $credit->Role->attributes();
                    $userDefinedValue = array_get($creditRoleAttributes, 'UserDefinedValue', '');
                }
            }

            $split = null;
            if (isset($credit->RightSharePercentage)) {
                $split = (string) $credit->RightSharePercentage;
            }

            $instrumentId = null;
            $instrumentUserDefinedValue = null;
            if (isset($credit->InstrumentType)) {
                $instrument = Instrument::where('ddex_key', $credit->InstrumentType)->first();

                if (!$instrument) {
                    Log::debug(sprintf('Missing instrument for %s: %s', $credit->InstrumentType));
                    $instrument = Instrument::where('ddex_key', 'UserDefined')->first();
                }

                if ((bool) $instrument->user_defined) {
                    $instrumentTypeAttributes = $credit->InstrumentType->attributes();
                    $instrumentUserDefinedValue = array_get($instrumentTypeAttributes, 'UserDefinedValue', '');
                }

                if ($instrument) {
                    $instrumentId = $instrument->getKey();
                }
            }

            $creditModel = Credit::where('contribution_type', $model->getType())->where('contribution_id', $model->getKey())->where('party_id', $contributionId)->first();

            if (!$creditModel) {
                $creditModel = Credit::updateOrCreate([
                    'party_id'                       => $contributionId,
                    'contribution_type'              => $model->getType(),
                    'contribution_id'                => $model->getKey(),
                    'credit_role_id'                 => $creditRoleId,
                ], [
                    'party_id'                       => $contributionId,
                    'contribution_type'              => $model->getType(),
                    'contribution_id'                => $model->getKey(),
                    'credit_role_id'                 => $creditRoleId,
                    'split'                          => $split,
                    'credit_role_user_defined_value' => $userDefinedValue,
                    'instrument_id'                  => $instrumentId,
                    'instrument_user_defined_value'  => $instrumentUserDefinedValue,
                ]);
            }

            $creditIds[] = $creditModel;
        }

        return $creditIds;
    }

    /**
     * Map the project element to an array.
     *
     * @param  SimpleXMLElement $project
     * @return array
     */
    private function mapProject(SimpleXMLElement $project): array
    {
        $projectId = (int) str_replace(self::PROJECT_ID_PREFIX, '', $project->ProjectReference);
        if ($this->masterProject->getKey()) {
            $projectId = $this->masterProject->getKey();
        }

        $projectNumber = '';
        if (isset($project->ProjectId->ProprietaryId)) {
            $projectNumber = (string) $project->ProjectId->ProprietaryId;
        }

        $labelId = (int) str_replace(self::PARTY_ID_PREFIX, '', (string) $project->Label);
        if (!Party::find($labelId)) {
            $labelId = null;
        }

        $mainArtistId = (int) str_replace(self::PARTY_ID_PREFIX, '', (string) $project->MainArtist);
        if (!Party::find($mainArtistId)) {
            $mainArtistId = null;
        }

        return [
            'id'             => $projectId,
            'name'           => (string) $project->Title,
            'description'    => (string) $project->Comment,
            'number'         => $projectNumber,
            'label_id'       => $labelId,
            'main_artist_id' => $mainArtistId,

            // Project credits.
            'credits'        => $project->Contributor,
        ];
    }

    /**
     * Import the parties into the database.s
     *
     * @param  array        $parties
     * @param  bool|boolean $override
     * @return array<Party>
     */
    private function importParties(array $parties, bool $override = false): array
    {
        $partyModels = [];

        foreach ($parties as $party) {
            $partyId = array_get($party, 'id', false);
            $party = array_except($party, ['id']);

            $partyModel = null;

            if ($override) {
                $partyModel = Party::where('id', $partyId)->userViewable(['user' => $this->projectOwner])->first();
            }

            if (!$partyModel) {
                $party['user_id'] = $this->projectOwner->getKey();
                $partyModel = new Party();
            }

            $partyModel->fill($party);
            $partyModel->save();
            $partyModels[$partyId] = $partyModel;
        }

        return $partyModels;
    }

    /**
     * Map a list of Party elements to an array of usable data.
     *
     * @param  SimpleXMLElement $parties
     * @return array
     */
    private function mapParties(SimpleXMLElement $parties): array
    {
        $partyData = [];

        foreach ($parties as $party) {
            $isOrganization = ((string) $party->IsOrganization) === "true";

            $partyId = (int) str_replace(self::PARTY_ID_PREFIX, '', $party->PartyReference);
            $firstName = (string) $party->PartyName->FullName;
            $middleName = '';
            $lastName = '';

            if (!$isOrganization) {
                $firstNames = explode(' ', (string) $party->PartyName->NamesBeforeKeyName);
                $firstName = array_shift($firstNames);
                $middleName = join(' ', $firstNames);
                $lastName = (string) $party->PartyName->KeyName;
            }

            $partyData[] = [
                'id'          => $partyId,
                'type'        => $isOrganization ? 'organisation' : 'person',
                'first_name'  => $firstName,
                'middle_name' => $middleName,
                'last_name'   => $lastName,
            ];
        }

        return $partyData;
    }

    /**
     * Import the recordings into the database.s
     *
     * @param  array        $recordings
     * @param  Project      $project
     * @param  bool|boolean $override
     * @return array<Party>
     */
    private function importRecordings(
        array $recordings,
        Project $project,
        array $songs,
        array $parties,
        array $sessions,
        bool $override = false
    ): array
    {
        $recordingModels = [];

        foreach ($recordings as $recording) {
            $recordingId = array_get($recording, 'id', false);
            $recording = array_except($recording, ['id']);

            $recording['project_id'] = $project->getKey();

            $partyId = array_get($recording, 'party_id', false);
            if ($partyId && isset($parties[$partyId])) {
                $party = $parties[$partyId];
                $recording['party_id'] = $party->getKey();
            }

            $songId = array_get($recording, 'song_id', false);
            if ($songId && isset($songs[$songId])) {
                $song = $songs[$songId];
                $recording['song_id'] = $song->getKey();
            }

            $recordingModel = null;
            if ($override) {
                $recordingModel = Recording::where('id', $recordingId)->userViewable(['user' => $this->projectOwner])->first();
            }

            if (!$recordingModel) {
                $recording['user_id'] = $this->projectOwner->getKey();
                $recordingModel = new Recording();
            }

            $recordingModel->fill(array_except($recording, ['sessions', 'credits']));
            $recordingModel->save();

            $this->importCredits($recordingModel, $recording['credits'], $parties);
            $this->importRecordingSessions($recordingModel, $recording['sessions'], $sessions);

            $recordingModels[$recordingId] = $recordingModel;
        }

        return $recordingModels;
    }


    private function importRecordingSessions(Recording $recordingModel, SimpleXMLElement $recordingSessions, array $allSessions)
    {
        $sessionIds = [];

        foreach ($recordingSessions as $session) {
            $sessionId = str_replace(self::SESSION_ID_PREFIX, '', $session);
            if (array_key_exists($sessionId, $allSessions)) {
                $sessionModel = array_get($allSessions, $sessionId);
                $sessionIds[] = $sessionModel->getKey();
            }
        }

        $recordingModel->sessions()->sync($sessionIds);
    }

    /**
     * Map the recording data to an array
     *
     * @param  SimpleXMLElement $recordings
     * @return array
     */
    private function mapRecordings(SimpleXMLElement $recordings): array
    {
        $recordingData = [];

        foreach ($recordings as $recording) {
            $recordingId = (int) str_replace(self::RECORDING_ID_PREFIX, '', $recording->ResourceReference);

            $recordingTypeId = null;
            $recordingTypeUserValue = null;
            $recordingType = RecordingType::select('recording_types.id', 'recording_types.ddex_key')->where('recording_types.ddex_key', (string) $recording->SoundRecordingType)->first();

            if (!$recordingType) {
                Log::debug(sprintf('Missing recording type %s', (string) $recording->SoundRecordingType));
                $recordingType = RecordingType::select('recording_types.id', 'recording_types.ddex_key')->where('recording_types.ddex_key', 'UserDefined')->first();
                $recordingTypeUserValue = (string) $recording->SoundRecordingType;
            }

            $recordingTypeId = $recordingType->getKey();

            $isrc = null;
            if (isset($recording->SoundRecordingId->ISRC)) {
                $isrc = (string) $recording->SoundRecordingId->ISRC;
            }

            $artistId = null;
            if (isset($recording->MainArtist)) {
                $artistId = (int) str_replace(self::PARTY_ID_PREFIX, '', $recording->MainArtist);
            }

            $songId = null;
            if (isset($recording->SoundRecordingMusicalWorkReference)) {
                $songId = (int) str_replace(self::SONG_ID_PREFIX, '', $recording->SoundRecordingMusicalWorkReference);
            }

            $title = null;
            $subTitle = null;
            if (isset($recording->Title)) {
                $title = (string) $recording->Title->TitleText;
                $subTitle = (string) $recording->Title->Subtitle;
            }

            $recordingData[] = [
                'id'                                => $recordingId,
                'isrc'                              => $isrc,
                'recording_type_id'                 => $recordingTypeId,
                'recording_type_user_defined_value' => $recordingTypeUserValue,
                'name'                              => $title,
                'subtitle'                          => $subTitle,
                'created_at'                        => Carbon::parse((string) $recording->CreationDate)->toDateTimeString(),
                'recorded_on'                       => Carbon::parse((string) $recording->EventDate)->toDateString(),
                'mixed_on'                          => Carbon::parse((string) $recording->MasteredDate)->toDateString(),
                'party_id'                          => $artistId,
                'song_id'                           => $songId,
                'description'                       => (string) $recording->Comment,
                'language'                          => (string) $recording->LanguageOfPerformance,
                'key_signature'                     => (string) $recording->KeySignature,
                'time_signature'                    => (string) $recording->TimeSignature,
                'tempo'                             => (string) $recording->Tempo,
                'version'                           => (string) $recording->Version,
                'duration'                          => Utilities::parseDuration((string) $recording->Duration),

                // Relations
                'credits'     => $recording->Contributor,
                'sessions'    => $recording->SoundRecordingSessionReference,
            ];
        }

        return $recordingData;
    }

    /**
     * Import the sessions into the database.s
     *
     * @param  array        $sessions
     * @param  Project      $project
     * @param  array        $parties
     * @param  bool|boolean $override
     * @return array<Party>
     */
    private function importSessions(array $sessions, Project $project, array $parties, bool $override = false): array
    {
        $sessionModels = [];

        foreach ($sessions as $session) {
            $sessionId = array_get($session, 'id', false);
            $session = array_except($session, ['id']);

            $session['project_id'] = $project->getKey();

            $venue = array_get($session, 'venue');
            unset($session['venue']);

            $session['venue_id'] = null;

            $venueModel = null;
            if ($override) {
                $venueModel = Venue::where('name', 'LIKE', '%' . $venue['name'] . '%')->userViewable(['user' => $this->projectOwner])->first();
            }

            if (!$venueModel) {
                $venueModel = Venue::create([
                    'user_id' => $this->projectOwner->getKey(),
                    'name'    => $venue['name'],
                    'country' => $venue['territory'],
                    'address' => $venue['address'],
                ]);
            }

            $session['venue_id'] = $venueModel->getKey();

            $sessionModel = null;
            if ($override) {
                $sessionModel = Session::where('id', $sessionId)->userViewable(['user' => $this->projectOwner])->first();
            }

            if (!$sessionModel) {
                $session['user_id'] = $this->projectOwner->getKey();
                $sessionModel = new Session();
            }

            $sessionModel->fill(array_except($session, ['credits']));
            $sessionModel->save();

            $this->importCredits($sessionModel, $session['credits'], $parties);

            $sessionModels[$sessionId] = $sessionModel;
        }

        return $sessionModels;
    }

    /**
     * Map the session data to an array
     *
     * @param  SimpleXMLElement $sessions
     * @return array
     */
    private function mapSessions(SimpleXMLElement $sessions): array
    {
        $sessionData = [];

        foreach ($sessions as $session) {
            $sessionId = (int) str_replace(self::SESSION_ID_PREFIX, '', $session->SessionReference);

            $sessionTypeId = null;
            $sessionTypeUserValue = null;
            $sessionType = SessionType::select('session_types.id', 'session_types.ddex_key')->where('session_types.ddex_key', (string) $session->SessionType)->first();

            if (!$sessionType) {
                Log::debug(sprintf('Missing session type %s', (string) $session->SessionType));
                $sessionType = SessionType::select('session_types.id', 'session_types.ddex_key')->where('session_types.ddex_key', 'Project')->first();
            }

            $sessionTypeId = $sessionType->getKey();

            // Timecode stuff
            $timecodeType = null;
            $timecodeFrameRate = null;
            $timecodeDropFrame = null;
            if (isset($session->TimeCode)) {
                $timecodeType = (string) $session->TimeCode->TimecodeType;
                $timecodeFrameRate = (string) $session->TimeCode->FrameRate;
                $timecodeDropFrame = (string) $session->TimeCode->TimecodeType;
            }

            $sessionData[] = [
                'id'                  => $sessionId,
                'session_type_id'     => $sessionTypeId,
                'description'         => (string) $session->Comment,
                'union_session'       => (string) $session->IsUnionSession === "true" ? 1 : 0,
                'analog_session'      => (string) $session->IsAnalogSession === "true" ? 1 : 0,
                'venue_room'          => (string) $session->VenueRoom,
                'started_at'          => Carbon::parse((string) $session->StartDateTime)->toDateTimeString(),
                'ended_at'            => Carbon::parse((string) $session->EndDateTime)->toDateTimeString(),
                'bit_depth'           => (string) $session->BitDepth,
                'sample_rate'         => (string) $session->SampleRate,
                'timecode_type'       => $timecodeType,
                'timecode_frame_rate' => $timecodeFrameRate,
                'drop_frame'          => $timecodeDropFrame == "true" ? 1 : 0,
                'name'                => (string) $session->VenueName . ', ' . Carbon::parse((string) $session->StartDateTime)->toDateTimeString(),

                // Relational data
                'recordings' => $session->SessionSoundRecordingReference,
                'credits'    => $session->Contributor,
                'venue'      => [
                    'name'      => (string) $session->VenueName,
                    'address'   => (string) $session->VenueAddress,
                    'territory' => (string) $session->TerritoryCode,
                ],
            ];
        }

        return $sessionData;
    }

    /**
     * Import the songs into the database.s
     *
     * @param  array        $songs
     * @param  array        $parties
     * @param  bool|boolean $override
     * @return array<Party>
     */
    private function importSongs(array $songs, array $parties, bool $override = false): array
    {
        $songModels = [];

        foreach ($songs as $song) {
            $songId = array_get($song, 'id', false);
            $song = array_except($song, ['id']);

            $songModel = null;
            if ($override) {
                $songModel = Song::where('id', $songId)->userViewable(['user' => $this->projectOwner])->first();
            }

            if (!$songModel) {
                $song['user_id'] = $this->projectOwner->getKey();
                $songModel = new Song();
            }

            $songModel->fill(array_except($song, ['credits']));
            $songModel->save();

            $this->importCredits($songModel, $song['credits'], $parties);

            $songModels[$songId] = $songModel;
        }

        return $songModels;
    }

    /**
     * Map the songs data to an array
     *
     * @param  SimpleXMLElement $songs
     * @return array
     */
    private function mapSongs(SimpleXMLElement $songs): array
    {
        $songData = [];

        foreach ($songs as $song) {
            $songId = (int) str_replace(self::SONG_ID_PREFIX, '', $song->MusicalWorkReference);

            $songTypeId = null;
            $songTypeUserValue = null;
            $songType = SongType::select('song_types.id', 'song_types.ddex_key')->where('song_types.ddex_key', (string) $song->MusicalWorkType)->first();

            if (!$songType) {
                Log::debug(sprintf('Missing song type %s', (string) $song->MusicalWorkType));
                $songType = SongType::select('song_types.id', 'song_types.ddex_key')->where('song_types.ddex_key', 'UserDefined')->first();
                $songTypeUserValue = (string) $song->MusicalWorkType;
            }

            $songTypeId = $songType->getKey();

            $title = null;
            $subTitle = null;
            if (isset($song->Title)) {
                $title = (string) $song->Title->TitleText;
                $subTitle = (string) $song->Title->Subtitle;
            }

            $titleAlt = null;
            $subTitleAlt = null;
            if (isset($song->AlternateTitle)) {
                $titleAlt = (string) $song->AlternateTitle->TitleText;
                $subTitleAlt = (string) $song->AlternateTitle->Subtitle;
            }

            $iswc = null;
            if (isset($song->MusicalWorkId->ISWC)) {
                $iswc = (string) $song->MusicalWorkId->ISWC;
            }

            $songData[] = [
                'id'                           => $songId,
                'iswc'                         => $iswc,
                'song_type_id'                 => $songTypeId,
                'song_type_user_defined_value' => $songTypeUserValue,
                'title'                        => $title,
                'subtitle'                     => $subTitle,
                'title_alt'                    => $titleAlt,
                'subtitle_alt'                 => $subTitleAlt,
                'lyrics'                       => (string) $song->Lyrics,
                'notes'                        => (string) $song->Comments,
                'created_on'                   => Carbon::parse((string) $song->CreationDate)->toDateString(),

                // Related credits
                'credits'                      => $song->Contributor,
            ];
        }

        return $songData;
    }
}
