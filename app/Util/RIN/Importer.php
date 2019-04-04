<?php

namespace App\Util\RIN;

use App\Models\Project;
use App\Models\SongType;
use App\Models\User;
use App\Models\Venue;
use App\Util\RIN\Utilities;
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
        $this->importProject($this->project, $override);
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

        $project['user_id'] = $this->currentUser->id;

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

        return [
            'id'             => $projectId,
            'name'           => (string) $project->Title,
            'description'    => (string) $project->Comment,
            'number'         => $projectNumber,
            'label_id'       => (int) str_replace(self::PARTY_ID_PREFIX, '', (string) $project->Label),
            'main_artist_id' => (int) str_replace(self::PARTY_ID_PREFIX, '', (string) $project->MainArtist),
        ];
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
            if (isset($recording->SoundRecordingId->MainArtist)) {
                $artistId = (int) str_replace(self::PARTY_ID_PREFIX, '', $recording->SoundRecordingId->MainArtist);
            }

            $recordingData[] = [
                'id'             => $recordingId,
                'isrc'           => $isrc,
                'name'           => (string) $recording->Title->TitleText,
                'recorded_on'    => (string) $recording->CreationDate,
                'party_id'       => $artistId,
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

            $venueId = null;
            $venue = Venue::select('venues.id', 'venues.name')->where('name', 'LIKE', '%' . (string) $session->VenueName . '%')->first();

            if ($venue) {
                $venueId = $venue->getKey();
            }

            // TODO: Maybe create the venue with the venue information if there isn't one?

            $sessionData[] = [
                'id'            => $sessionId,
                'venue_id'      => $venueId,
                'description'   => (string) $session->Comment,
                'union_session' => (string) $session->IsUnionSession === "true" ? 1 : 0,
                'venue_room'    => (string) $session->VenueRoom,
                'recordings'    => $session->SessionSoundRecordingReference,
            ];
        }

        return $sessionData;
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