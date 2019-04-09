<?php

namespace App\Util\RIN;

use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use DOMDocument;
use DOMElement;
use Illuminate\Support\Facades\App;
use SimpleXMLElement;

class Exporter
{
    const FILE_ID_PREFIX = 'VeVa-Project-';

    const PROJECT_ID_PREFIX = 'J-';
    const PARTY_ID_PREFIX = 'P-';
    const SESSION_ID_PREFIX = 'O-';
    const SONG_ID_PREFIX = 'W-';
    const RECORDING_ID_PREFIX = 'A-';

    private $currentUser;
    private $version;
    private $project;

    public function __construct(Project $project, $version = 1)
    {
        $this->project = $project;
        $this->version = $version;
    }

    /**
     * Set the current user context.
     *
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * Output an XML document which we can then use to
     * output a file for a client.
     *
     * @return string
     */
    public function toXML(): string
    {
        $document = new DOMDocument("1.0", "UTF-8");
        $rin = $this->boilerplateXML($document);

        // TODO: Start appending </FileHeader> and it's fields.

        $fileHeader = $this->fileHeader($document);
        $projectList = $this->projectList($document);
        $sessionList = $this->sessionList($document);
        $recordingList = $this->recordingList($document);
        $songList = $this->songList($document);

        $rin->appendChild($fileHeader);
        $rin->appendChild($projectList);
        $rin->appendChild($sessionList);
        $rin->appendChild($recordingList);
        $rin->appendChild($songList);

        $document->appendChild($rin);

        return $document->saveXML();
    }

    private function filename()
    {
        $identifier = time();

        if (!is_null($this->project->number)) {
            $identifier = time() . '_' . $this->project->number;
        }

        return sprintf('%s_rin.xml', $identifier);
    }

    private function boilerplateXML(DOMDocument $document): DOMElement
    {
        $rinElement = $document->createElementNS('http://ddex.net/xml/f-rin/10', 'rin:RecordingInformationNotification');
        $rinElement->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:avs', 'http://ddex.net/xml/avs/avs');
        $rinElement->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ds', 'http://www.w3.org/2000/09/xmldsig#');
        $rinElement->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:schemaLocation',  'http://ddex.net/xml/f-rin/10 http://ddex.net/xml/rin/10/full-recording-information-notification.xsd');
        $rinElement->setAttribute('SchemaVersionId', 'f-rin/10');
        $rinElement->setAttribute('LanguageAndScriptCode', 'en');

        return $rinElement;
    }

    private function fileHeader(DOMDocument $document): DOMElement
    {
        $fileHeader = $document->createElement('FileHeader');

        $fileHeader->appendChild($document->createElement('FileId', self::FILE_ID_PREFIX . $this->version));
        $fileHeader->appendChild($document->createElement('FileName', $this->filename()));
        $fileHeader->appendChild($document->createElement('FileCreatedDateTime', Carbon::now()->toIso8601String()));
        $fileHeader->appendChild($document->createElement('FileControlType', App::environment('production') ? 'LiveMessage' : 'TestMessage'));
        $fileHeader->appendChild($document->createElement('SystemType', config('app.name', '')));
        $fileHeader->appendChild($document->createElement('Version', config('app.version', '')));

        $fileCreator = $document->createElement('FileCreator');
        $fileCreator->appendChild($document->createElement('PartyId'));
        $fileHeader->appendChild($fileCreator);

        $createdOnBehalfOf = $document->createElement('CreatedOnBehalfOf');
        $createdOnBehalfOf->appendChild($document->createElement('PartyId'));
        $fileHeader->appendChild($createdOnBehalfOf);

        $fileHeader->appendChild($this->signature($document));

        return $fileHeader;
    }

    private function projectList(DOMDocument $document): DOMElement
    {
        $projectList = $document->createElement('ProjectList');

        $project = $document->createElement('Project');

        $projectId = $document->createElement('ProjectId');
        $project->appendChild($projectId);

        $proprietaryId = $document->createElement('ProprietaryId', $this->project->number);
        $proprietaryId->setAttribute('Namespace', 'Project Number');
        $projectId->appendChild($proprietaryId);
        $project->appendChild($document->createElement('ProjectReference', self::PROJECT_ID_PREFIX . $this->project->getKey()));

        if ($this->project->artist) {
            $project->appendChild($document->createElement('MainArtist', self::PARTY_ID_PREFIX . $this->project->artist->getKey()));
            $project->appendChild($document->createElement('ProjectArtist', $this->project->artist->name));
        }

        $project->appendChild($document->createElement('Title', $this->project->name));

        if ($this->project->label) {
            $project->appendChild($document->createElement('Label', self::PARTY_ID_PREFIX . $this->project->label->getKey()));
        }

        $project->appendChild($document->createElement('Status', 'Verified'));

        $credits = $this->project->credits()->where('contribution_type', 'project')->where('contribution_id', $this->project->getKey())->get();
        foreach ($credits as $credit) {
            $contributorReference = $document->createElement('ContributorReference');
            $contributorReference->appendChild($document->createElement('ProjectContributorReference', self::PARTY_ID_PREFIX . $credit->party_id));
            $contributorReference->appendChild($document->createElement('Role', $credit->role->name));

            if (!is_null($credit->split)) {
                $contributorReference->appendChild($document->createElement('RightSharePercentage', $credit->split));
            }

            $project->appendChild($contributorReference);
        }

        $projectList->appendChild($project);

        return $projectList;
    }

    private function sessionList(DOMDocument $document): DOMElement
    {
        $sessionList = $document->createElement('SessionList');

        $sessionModels = $this->project->sessions;
        foreach ($sessionModels as $sessionModel) {
            $session = $document->createElement('Session');
            $session->appendChild($document->createElement('SessionReference', self::SESSION_ID_PREFIX . $sessionModel->getKey()));

            $session->appendChild($document->createElement('SessionType', $sessionModel->type->name));
            $session->appendChild($document->createElement('VenueName', $sessionModel->venue->name));
            $session->appendChild($document->createElement('VenueAddress', $sessionModel->venue->address));

            // TODO: this will be changing to an id, so pull the code that way.
            $session->appendChild($document->createElement('TerritoryCode', $sessionModel->venue->country));

            $session->appendChild($document->createElement('VenueRoom', $sessionModel->venue_room));
            $session->appendChild($document->createElement('IsUnionSession', $sessionModel->union_session ? 'true' : 'false'));
            $session->appendChild($document->createElement('IsAnalogSession', $sessionModel->analog_session ? 'true' : 'false'));
            $session->appendChild($document->createElement('Comment', $sessionModel->description));

            if (!is_null($sessionModel->started_at)) {
                $dt = Carbon::parse($sessionModel->started_at);
                $session->appendChild($document->createElement('StartDateTime', $dt->toIso8601String()));
            }

            if (!is_null($sessionModel->ended_at)) {
                $dt = Carbon::parse($sessionModel->ended_at);
                $session->appendChild($document->createElement('EndDateTime', $dt->toIso8601String()));
            }

            if (!is_null($sessionModel->bit_depth)) {
                $session->appendChild($document->createElement('BitDepth', $sessionModel->bit_depth));
            }

            if (!is_null($sessionModel->sample_rate)) {
                $session->appendChild($document->createElement('SampleRate', $sessionModel->sample_rate));
            }

            if (!is_null($sessionModel->timecode_type)) {
                $timecode = $document->createElement('TimeCode');
                $timecode->appendChild($document->createElement('TimecodeType', $sessionModel->timecode_type));
                $timecode->appendChild($document->createElement('FrameRate', $sessionModel->timecode_frame_rate));
                $timecode->appendChild($document->createElement('IsDropFrame', $sessionModel->drop_frame ? 'true' : 'false'));

                $session->appendChild($timecode);
            }

            $recordingModels = $sessionModel->recordings;
            foreach ($recordingModels as $recordingModel) {
                $session->appendChild($document->createElement('SessionSoundRecordingReference', self::RECORDING_ID_PREFIX . $recordingModel->getKey()));
            }

            $sessionList->appendChild($session);
        }

        return $sessionList;
    }

    private function recordingList(DOMDocument $document): DOMElement
    {
        $recordingList = $document->createElement('ResourceList');

        $recordingModels = $this->project->recordings;
        foreach ($recordingModels as $recordingModel) {
            if (!$recordingModel->song) {
                continue;
            }

            $soundRecording = $document->createElement('SoundRecording');

            if (!is_null($recordingModel->type)) {
                $soundRecording->appendChild($document->createElement('SoundRecordingType', $recordingModel->type->name));
            }

            if (!is_null($recordingModel->party)) {
                $soundRecording->appendChild($document->createElement('MainArtist', self::PARTY_ID_PREFIX . $recordingModel->party->getKey()));
            }

            if (!is_null($recordingModel->isrc)) {
                $soundRecordingId = $document->createElement('SoundRecordingId');
                $soundRecordingId->appendChild($document->createElement('ISRC', $recordingModel->isrc));
                $soundRecording->appendChild($soundRecordingId);
            }

            $soundRecording->appendChild($document->createElement('ResourceReference', self::RECORDING_ID_PREFIX . $recordingModel->getKey()));
            $soundRecording->appendChild($document->createElement('SoundRecordingMusicalWorkReference', self::SONG_ID_PREFIX . $recordingModel->song->getKey()));

            $title = $document->createElement('Title');
            $title->appendChild($document->createElement('TitleText', $recordingModel->name));
            $title->appendChild($document->createElement('SubTitle', $recordingModel->subtitle));
            $soundRecording->appendChild($title);

            $soundRecording->appendChild($document->createElement('Version', $recordingModel->version));
            $soundRecording->appendChild($document->createElement('LanguageOfPerformance', $recordingModel->language));
            $soundRecording->appendChild($document->createElement('Comment', $recordingModel->description));
            $soundRecording->appendChild($document->createElement('KeySignature', $recordingModel->key_signature));
            $soundRecording->appendChild($document->createElement('TimeSignature', $recordingModel->time_signature));
            $soundRecording->appendChild($document->createElement('Tempo', $recordingModel->tempo));

            $soundRecording->appendChild($document->createElement('Duration', Utilities::formatDuration($recordingModel->duration)));
            if (!is_null($recordingModel->mixed_on)) {
                $dt = Carbon::parse($recordingModel->mixed_on);
                $soundRecording->appendChild($document->createElement('MasteredDate', $dt->toIso8601String()));
            }

            if (!is_null($recordingModel->recorded_on)) {
                $dt = Carbon::parse($recordingModel->recorded_on);
                $soundRecording->appendChild($document->createElement('EventDate', $dt->toIso8601String()));
            }

            if (!is_null($recordingModel->created_at)) {
                $dt = Carbon::parse($recordingModel->created_at);
                $soundRecording->appendChild($document->createElement('CreationDate', $dt->toIso8601String()));
            }

            $creditModels = $recordingModel->credits()->where('contribution_type', 'recording')->where('contribution_id', $recordingModel->getKey())->get();
            foreach ($creditModels as $creditModel) {
                $contributorReference = $document->createElement('ContributorReference');
                $contributorReference->appendChild($document->createElement('SoundRecordingContributorReference', self::PARTY_ID_PREFIX . $creditModel->party_id));
                $contributorReference->appendChild($document->createElement('Role', $creditModel->role->name));

                if (!is_null($creditModel->split)) {
                    $contributorReference->appendChild($document->createElement('RightSharePercentage', $creditModel->split));
                }

                $soundRecording->appendChild($contributorReference);
            }

            $recordingList->appendChild($soundRecording);
        }

        return $recordingList;
    }

    private function songList(DOMDocument $document): DOMElement
    {
        $songList = $document->createElement('MusicalWorkList');

        $this->project->load('recordings.song');
        $songModels = $this->project->recordings->pluck('song')->all();
        foreach ($songModels as $songModel) {
            $musicalWork = $document->createElement('MusicalWork');

            if (!is_null($songModel->iswc)) {
                $musicalWorkId = $document->createElement('MuscialWorkId');
                $musicalWorkId->appendChild($document->createElement('ISWC', $songModel->iswc));
                $musicalWork->appendChild($musicalWorkId);
            }

            $musicalWork->appendChild($document->createElement('MusicalWorkReference', self::SONG_ID_PREFIX . $songModel->getKey()));
            $musicalWork->appendChild($document->createElement('CreationDate', Carbon::parse($songModel->created_on)->toDateString()));
            $musicalWork->appendChild($document->createElement('Lyrics', $songModel->lyrics));
            $musicalWork->appendChild($document->createElement('Comment', $songModel->notes));

            $title = $document->createElement('Title');
            $title->appendChild($document->createElement('TitleText', $songModel->title));
            $title->appendChild($document->createElement('SubTitle', $songModel->subtitle));
            $musicalWork->appendChild($title);

            $altTitle = $document->createElement('AlternateTitle');
            $altTitle->appendChild($document->createElement('TitleText', $songModel->title_alt));
            $altTitle->appendChild($document->createElement('SubTitle', $songModel->subtitle_alt));
            $musicalWork->appendChild($altTitle);

            $musicalWork->appendChild($document->createElement('MusicalWorkType', $songModel->type->name));

            $creditModels = $songModel->credits()->where('contribution_type', 'song')->where('contribution_id', $songModel->getKey())->get();
            foreach ($creditModels as $creditModel) {
                $contributorReference = $document->createElement('ContributorReference');
                $contributorReference->appendChild($document->createElement('MusicalWorkContributorReference', self::PARTY_ID_PREFIX . $creditModel->party_id));
                $contributorReference->appendChild($document->createElement('Role', $creditModel->role->name));

                if (!is_null($creditModel->split)) {
                    $contributorReference->appendChild($document->createElement('RightSharePercentage', $creditModel->split));
                }

                $musicalWork->appendChild($contributorReference);
            }

            $songList->appendChild($musicalWork);
        }

        return $songList;
    }

    private function signature(DOMDocument $document): DOMElement
    {
        $signature = $document->createElement('Signature');
        $signedInfo = $document->createElement('SignedInfo');

        $canonicalizationMethod = $document->createElement('ds:CanonicalizationMethod');
        $signatureMethod = $document->createElement('ds:SignatureMethod');

        $reference = $document->createElement('ds:Reference');
        $digestMethod = $document->createElement('ds:DigestMethod');
        $digestValue = $document->createElement('ds:DigestValue');
        $reference->appendChild($digestMethod);
        $reference->appendChild($digestValue);

        $signedInfo->appendChild($canonicalizationMethod);
        $signedInfo->appendChild($signatureMethod);
        $signedInfo->appendChild($reference);

        $signatureValue = $document->createElement('SignatureValue');

        $keyInfo = $document->createElement('KeyInfo');
        $x509Data = $document->createElement('X509Data');
        $x509Data->appendChild($document->createElement('X509SubjectName'));
        $keyInfo->appendChild($x509Data);

        $signature->appendChild($signedInfo);
        $signature->appendChild($signatureValue);
        $signature->appendChild($keyInfo);

        return $signature;
    }
}
