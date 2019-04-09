<?php

namespace App\Util\RIN;

use App\Models\Credit;
use App\Models\CreditRole;
use App\Models\Party;
use App\Models\Project;
use App\Models\Recording;
use App\Models\Session;
use App\Models\Song;
use App\Models\SongType;
use App\Models\User;
use App\Models\Venue;
use App\Util\RIN\Utilities;
use Illuminate\Support\Facades\DB;
use SimpleXMLElement;

// TODO:
// This class should take in a valid XML document which is a RIN
// file. This contains information about the project in the <FileHeader/>
// Query the project via the <FileId/> format "VeVa-Project-{internalId}"
// Go through the file and grab the parties, sessions, recordings, musical works (songs)
// then build up a relational map of data, which I can then use to make the associations.
// Wrap any DB changes in a transaction, and discard the changes if anything goes wrong.

// If the Project doesn't exist then don't create it?

class Importer
{
    const PROJECT_ID_PREFIX = 'J-';
    const PARTY_ID_PREFIX = 'P-';
    const SESSION_ID_PREFIX = 'O-';
    const SONG_ID_PREFIX = 'W-';
    const RECORDING_ID_PREFIX = 'A-';

    private $currentUser;

    private $fileId;
    private $project;
    private $parties;
    private $recordings;
    private $sessions;
    private $songs;

    /**
     * Import a RIN from an XML document object.
     *
     * @param  SimpleXMLElement $xml
     * @return Importer
     */
    public function fromXML(SimpleXMLElement $xml): Importer
    {
        $this->fileId = $xml->FileHeader->FileId;

        $this->project = $this->mapProject($xml->ProjectList->Project);
        $this->parties = $this->mapParties($xml->PartyList->children());

        $this->recordings = $this->mapRecordings($xml->ResourceList->children());
        $this->sessions = $this->mapSessions($xml->SessionList->children());
        $this->songs = $this->mapSongs($xml->MusicalWorkList->children());

        return $this;
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
            $project = $this->importProject($this->project, $override);
            $songs = $this->importSongs($this->songs, $override);
            $sessions = $this->importSessions($this->sessions, $project, $override);
            $recordings = $this->importRecordings($this->recordings, $project, $songs, $parties, $sessions, $override);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            throw $e;
        }
    }

    /**
     * Set the current user.
     *
     * @param User $user
     */
    public function setUser(User $user): Importer
    {
        $this->currentUser = $user;
        return $this;
    }

    /**
     * Import the project into the database.s
     *
     * @param  array        $project
     * @param  bool|boolean $override
     * @return Project
     */
    private function importProject(array $project, bool $override = false): Project
    {
        $projectId = array_get($project, 'id', false);
        $project = array_except($project, ['id']);

        $project['user_id'] = $this->currentUser->getKey();

        if ($override) {
            $projectModel = Project::where('id', $projectId)->userViewable(['user' => $this->currentUser])->first();

            if (!$projectModel) {
                $projectModel = new Project();
            }

            $projectModel->fill($project);
            $projectModel->save();
            return $projectModel;
        }

        return Project::create($project);
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
                $partyModel = Party::where('id', $partyId)->userViewable(['user' => $this->currentUser])->first();
            }

            if (!$partyModel) {
                $party['user_id'] = $this->currentUser->getKey();
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
                $recordingModel = Recording::where('id', $recordingId)->userViewable(['user' => $this->currentUser])->first();
            }

            if (!$recordingModel) {
                $recording['user_id'] = $this->currentUser->getKey();
                $recordingModel = new Recording();
            }

            $recordingModel->fill($recording);
            $recordingModel->save();

            $this->importRecordingCredits($recordingModel, $recording['credits'], $parties);
            $this->importRecordingSessions($recordingModel, $recording['sessions'], $sessions);

            $recordingModels[$recordingId] = $recordingModel;
        }

        return $recordingModels;
    }

    private function importRecordingCredits(Recording $recordingModel, SimpleXMLElement $recordingCredits, array $allParties)
    {
        $recordingCreditIds = [];
        foreach($recordingCredits as $credit) {
            $contributionId = (int) str_replace(self::PARTY_ID_PREFIX, '', (string) $credit->SoundRecordingContributorReference);
            $contributionRole = (string) $credit->Role;

            if (array_key_exists($contributionId, $allParties)) {
                $contributionModel = array_get($allParties, $contributionId);
                $contributionId = $contributionModel->getKey();
            }

            $creditRoleId = null;
            $creditRole = CreditRole::where('type', 'recording')->where('name', $contributionRole)->first();
            if ($creditRole) {
                $creditRoleId = $creditRole->getKey();
            }

            $creditModel = Credit::where('contribution_type', 'recording')->where('contribution_id', $recordingModel->getKey())->where('party_id', $contributionId)->first();

            // TODO: Handle ContributorType correctly
            // TODO: InstrumentType handling
            // TODO: handle RightSharePercentage, "split"

            if (!$creditModel) {
                $creditModel = Credit::create([
                    'party_id'          => $contributionId,
                    'contribution_type' => 'recording',
                    'contribution_id'   => $recordingModel->getKey(),
                    'credit_role_id'    => $creditRoleId,
                ]);
            }

            $recordingCreditIds[] = $creditModel;
        }
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

            $recordingData[] = [
                'id'             => $recordingId,
                'isrc'           => $isrc,
                'name'           => (string) $recording->Title->TitleText,
                'recorded_on'    => (string) $recording->CreationDate,
                'party_id'       => $artistId,
                'song_id'        => $songId,
                'description'    => (string) $recording->Comment,
                'language'       => (string) $recording->LanguageOfPerformance,
                'key_signature'  => (string) $recording->KeySignature,
                'time_signature' => (string) $recording->TimeSignature,
                'tempo'          => (string) $recording->Tempo,
                'duration'       => Utilities::parseDuration((string) $recording->Duration),

                // Relations
                'credits'     => $recording->ContributorReference,
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
     * @param  bool|boolean $override
     * @return array<Party>
     */
    private function importSessions(array $sessions, Project $project, bool $override = false): array
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
                $venueModel = Venue::where('name', 'LIKE', '%' . $venue['name'] . '%')->userViewable(['user' => $this->currentUser])->first();
            }

            if (!$venueModel) {
                $venueModel = Venue::create([
                    'user_id' => $this->currentUser->getKey(),
                    'name'    => $venue['name'],
                    'country' => $venue['territory'],
                    'address' => $venue['address'],
                ]);
            }

            $session['venue_id'] = $venueModel->getKey();

            $sessionModel = null;
            if ($override) {
                $sessionModel = Session::where('id', $sessionId)->userViewable(['user' => $this->currentUser])->first();
            }

            if (!$sessionModel) {
                $session['user_id'] = $this->currentUser->getKey();
                $sessionModel = new Session();
            }

            $sessionModel->fill($session);
            $sessionModel->save();
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

            $sessionData[] = [
                'id'            => $sessionId,
                'venue'         => [
                    'name'      => (string) $session->VenueName,
                    'address'   => (string) $session->VenueAddress,
                    'territory' => (string) $session->TerritoryCode,
                ],
                'description'   => (string) $session->Comment,
                'union_session' => (string) $session->IsUnionSession === "true" ? 1 : 0,
                'venue_room'    => (string) $session->VenueRoom,
                'recordings'    => $session->SessionSoundRecordingReference,
            ];
        }

        return $sessionData;
    }

    /**
     * Import the songs into the database.s
     *
     * @param  array        $songs
     * @param  bool|boolean $override
     * @return array<Party>
     */
    private function importSongs(array $songs, bool $override = false): array
    {
        $songModels = [];

        foreach ($songs as $song) {
            $songId = array_get($song, 'id', false);
            $song = array_except($song, ['id']);

            $songModel = null;
            if ($override) {
                $songModel = Song::where('id', $songId)->userViewable(['user' => $this->currentUser])->first();
            }

            if (!$songModel) {
                $song['user_id'] = $this->currentUser->getKey();
                $songModel = new Song();
            }

            $songModel->fill($song);
            $songModel->save();
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
            $songType = SongType::select('song_types.id', 'song_types.name')->where('name', 'LIKE', '%'. (string) $song->MusicalWorkType .'%')->first();

            if ($songType) {
                $songTypeId = $songType->getKey();
            }

            $songData[] = [
                'id'           => $songId,
                'song_type_id' => $songTypeId,
                'title'        => (string) $song->Title->TitleText,
                'credits'      => $song->ContributorReference,
            ];
        }

        return $songData;
    }
}
