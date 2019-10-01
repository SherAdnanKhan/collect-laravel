<?php

namespace App\Util\RIN;

use App\Contracts\Creditable;
use App\Models\Party;
use App\Models\Project;
use App\Models\User;
use App\Models\Recording;
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
    private $filename;
    private $recording;

    public function __construct(Project $project, $version = 1)
    {
        $this->project = $project;
        $this->version = $version;
        $this->filename = $this->generateFilename();
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
     * Set the recording context to filter by a specific recording.
     *
     * @param Recording|null $recording
     */
    public function setRecording($recording)
    {
        $this->recording = $recording;
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

        $fileHeader = $this->fileHeader($document);
        $partyList = $this->partyList($document);
        $songList = $this->songList($document);
        $recordingList = $this->recordingList($document);
        $projectList = $this->projectList($document);
        $sessionList = $this->sessionList($document);

        $rin->appendChild($fileHeader);
        $rin->appendChild($partyList);
        $rin->appendChild($songList);
        $rin->appendChild($recordingList);
        $rin->appendChild($projectList);
        $rin->appendChild($sessionList);

        $document->appendChild($rin);

        $document->preserveWhiteSpace = false;
        $document->formatOutput = true;

        return $document->saveXML();
    }

    public function getFilename()
    {
        return $this->filename;
    }

    private function generateFilename()
    {
        $identifier = time();

        if (!is_null($this->project->number)) {
            $identifier = time() . '_' . $this->project->number;
        }

        return sprintf('%s_rin.xml', $identifier);
    }

    private function boilerplateXML(DOMDocument $document): DOMElement
    {
        $rinElement = $document->createElementNS('http://ddex.net/xml/f-rin/11', 'rin:RecordingInformationNotification');
        // $rinElement->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:avs', 'http://ddex.net/xml/avs/avs');
        $rinElement->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ds', 'http://www.w3.org/2000/09/xmldsig#');
        $rinElement->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $rinElement->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'xsi:schemaLocation',  'http://ddex.net/xml/f-rin/11 http://ddex.net/xml/rin/11/full-recording-information-notification.xsd');
        $rinElement->setAttribute('SchemaVersionId', 'f-rin/11');
        $rinElement->setAttribute('LanguageAndScriptCode', 'en');

        return $rinElement;
    }

    private function fileHeader(DOMDocument $document): DOMElement
    {
        $fileHeader = $document->createElement('FileHeader');

        $fileHeader->appendChild($document->createElement('FileId', self::FILE_ID_PREFIX . $this->project->id));
        $fileHeader->appendChild($document->createElement('FileName', $this->filename));
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

    private function partyList(DOMDocument $document): DOMElement
    {
        $partyList = $document->createElement('PartyList');

        $partyModels = Party::relatedToProject(['project' => $this->project])->get();

        foreach ($partyModels as $partyModel) {
            $party = $document->createElement('Party');

            $partyId = $document->createElement('PartyId');
            if (!is_null($partyModel->isni)) {
                $partyId->appendChild($document->createElement('ISNI', $partyModel->isni));
            }
            $party->appendChild($partyId);

            $party->appendChild($document->createElement('PartyReference', self::PARTY_ID_PREFIX . $partyModel->getKey()));

            $partyName = $document->createElement('PartyName');
            $partyName->appendChild($document->createElement('FullName', $partyModel->name));
            $partyName->appendChild($document->createElement('FullNameIndexed', $partyModel->indexed_name));
            $partyName->appendChild($document->createElement('NamesBeforeKeyName', $partyModel->non_key_names));
            $partyName->appendChild($document->createElement('KeyName', $partyModel->last_name));
            $partyName->appendChild($document->createElement('AbbreviatedName', $partyModel->initials));
            $party->appendChild($partyName);

            $party->appendChild($document->createElement('IsOrganization', $partyModel->type != 'person' ? 'true' : 'false'));

            $addressModels = $partyModel->addresses;
            foreach ($addressModels as $i => $addressModel) {
                $postalAddress = $document->createElement('PostalAddress');
                $postalAddress->setAttribute('SequenceNumber', $i);

                $addressLine1 = $document->createElement('PostalAddressLine', $addressModel->line_1);
                $postalAddress->appendChild($addressLine1);
                $addressLine2 = $document->createElement('PostalAddressLine', $addressModel->line_2);
                $postalAddress->appendChild($addressLine2);
                $addressLine3 = $document->createElement('PostalAddressLine', $addressModel->line_3);
                $postalAddress->appendChild($addressLine3);

                $postalAddress->appendChild($document->createElement('CityName', $addressModel->city));

                if (!empty($addressModel->district)) {
                    $postalAddress->appendChild($document->createElement('DistrictName', $addressModel->district));
                }

                if (!empty($addressModel->postal_code)) {
                    $postalAddress->appendChild($document->createElement('PostCode', $addressModel->postal_code));
                }

                if (!empty($addressModel->territory_code)) {
                    $postalAddress->appendChild($document->createElement('TerritoryCode', $addressModel->territory_code));
                }

                $party->appendChild($postalAddress);
            }

            $contactModels = $partyModel->contacts()->orderBy('type')->get();
            foreach ($contactModels as $i => $contactModel) {
                $elementType = 'EmailAddress';
                if ($contactModel->type == 'phone') {
                    $elementType = 'PhoneNumber';
                }

                $contactElement = $document->createElement($elementType, $contactModel->value);
                $contactElement->setAttribute('SequenceNumber', $i);
                $party->appendChild($contactElement);
            }

            if (!is_null($partyModel->birth_date)) {
                $party->appendChild($document->createElement(
                    'DateAndPlaceOfBirth',
                    Carbon::parse($partyModel->birth_date)->toDateString()
                ));
            }

            if (!is_null($partyModel->death_date)) {
                $party->appendChild($document->createElement(
                    'DateAndPlaceOfDeath',
                    Carbon::parse($partyModel->death_date)->toDateString()
                ));
            }

            $partyList->appendChild($party);
        }

        return $partyList;
    }

    private function projectList(DOMDocument $document): DOMElement
    {
        $projectList = $document->createElement('ProjectList');

        $project = $document->createElement('Project');

        $projectId = $document->createElement('ProjectId');

        $proprietaryId = $document->createElement('ProprietaryId', $this->project->number);
        $proprietaryId->setAttribute('Namespace', 'Project Number');
        $projectId->appendChild($proprietaryId);

        $project->appendChild($projectId);
        $project->appendChild($document->createElement('ProjectReference', self::PROJECT_ID_PREFIX . $this->project->getKey()));

        if ($this->project->artist) {
            $displayArtist = $document->createElement('DisplayArtist');
            $displayArtist->appendChild($document->createElement('PartyReference', self::PARTY_ID_PREFIX . $this->project->artist->getKey()));
            $project->appendChild($displayArtist);
            $project->appendChild($document->createElement('ProjectArtist', $this->project->artist->name));
        }

        $project->appendChild($document->createElement('ProjectName', $this->project->name));

        if ($this->project->label) {
            $project->appendChild($document->createElement('ProjectLabelReference', self::PARTY_ID_PREFIX . $this->project->label->getKey()));
        }

        $project->appendChild($document->createElement('Status', 'Verified'));
        $project = $this->creditList($document, $project, $this->project);

        $projectList->appendChild($project);

        return $projectList;
    }

    private function sessionList(DOMDocument $document): DOMElement
    {
        $sessionList = $document->createElement('SessionList');

        $sessionModels = $this->recording ? $this->recording->sessions : $this->project->sessions;

        foreach ($sessionModels as $sessionModel) {
            $session = $document->createElement('Session');
            $session->appendChild($document->createElement('SessionReference', self::SESSION_ID_PREFIX . $sessionModel->getKey()));

            $session->appendChild($document->createElement('SessionType', $sessionModel->type->ddex_key));

            if (!is_null($sessionModel->started_at)) {
                $dt = Carbon::parse($sessionModel->started_at);
                $session->appendChild($document->createElement('StartDateTime', $dt->toIso8601String()));
            }

            if (!is_null($sessionModel->ended_at)) {
                $dt = Carbon::parse($sessionModel->ended_at);
                $session->appendChild($document->createElement('EndDateTime', $dt->toIso8601String()));
            }

            if (!is_null($sessionModel->venue)) {
                $venue = $document->createElement('Venue');
                $venue->appendChild($document->createElement('VenueName', $sessionModel->venue->name));
                $venue->appendChild($document->createElement('VenueAddress', $sessionModel->venue->address));

                $countryCode = 'US';
                if (!is_null($sessionModel->venue->country)) {
                    $countryCode = $sessionModel->venue->country->iso_code;
                }

                $venue->appendChild($document->createElement('TerritoryCode', $countryCode));
                $venue->appendChild($document->createElement('VenueRoom', $sessionModel->venue_room));
                $session->appendChild($venue);
            }

            $session->appendChild($document->createElement('IsUnionSession', $sessionModel->union_session ? 'true' : 'false'));
            $session->appendChild($document->createElement('IsAnalogSession', $sessionModel->analog_session ? 'true' : 'false'));
            $session->appendChild($document->createElement('Comment', $sessionModel->description));


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

            $session = $this->creditList($document, $session, $sessionModel);

            $sessionList->appendChild($session);
        }

        return $sessionList;
    }

    private function recordingList(DOMDocument $document): DOMElement
    {
        $recordingList = $document->createElement('ResourceList');

        $recordingModels = $this->recording ? [$this->recording] : $this->project->recordings;
        foreach ($recordingModels as $recordingModel) {
            if (!$recordingModel->song) {
                continue;
            }

            $soundRecording = $document->createElement('SoundRecording');

            $recordingTypeKey = 'Unknown';
            if (!is_null($recordingModel->type)) {
                $recordingTypeKey = $recordingModel->type->ddex_key;
            }

            $recordingType = $document->createElement('Type', $recordingTypeKey);

            if (!is_null($recordingModel->type) && (bool) $recordingModel->type->user_defined) {
                $recordingType->setAttribute('UserDefinedValue', $recordingModel->recording_type_user_defined_value);
            }

            $soundRecording->appendChild($recordingType);

            if (!is_null($recordingModel->party)) {
                $soundRecording->appendChild($document->createElement('DisplayArtistName', $recordingModel->party->name));
                $displayArtist = $document->createElement('DisplayArtist');
                $displayArtist->appendChild($document->createElement('PartyReference', self::PARTY_ID_PREFIX . $recordingModel->party->getKey()));

                $titleDisplayInformation = $document->createElement('TitleDisplayInformation');
                $titleDisplayInformation->appendChild($document->createElement('IsDisplayedInTitle', 'false'));

                $displayArtist->appendChild($titleDisplayInformation);

                if (!is_null($recordingModel->partyRole)) {
                    $artisticRole = $document->createElement('ArtisticRole', $recordingModel->partyRole->ddex_key);

                    if ((bool) $recordingModel->partyRole->user_defined) {
                        $artisticRole->setAttribute('UserDefinedValue', $recordingModel->party_role_user_defined_value);
                    }
                } else {
                    $artisticRole = $document->createElement('ArtisticRole', 'Unknown');
                }

                $displayArtist->appendChild($artisticRole);
                $soundRecording->appendChild($displayArtist);
            }

            if (!is_null($recordingModel->isrc)) {
                $soundRecordingId = $document->createElement('SoundRecordingId');
                $soundRecordingId->appendChild($document->createElement('ISRC', $recordingModel->isrc));
                $soundRecording->appendChild($soundRecordingId);
            }

            $soundRecording->appendChild($document->createElement('ResourceReference', self::RECORDING_ID_PREFIX . $recordingModel->getKey()));

            $title = $document->createElement('Title');
            $title->appendChild($document->createElement('TitleText', $recordingModel->name));
            $title->appendChild($document->createElement('SubTitle', $recordingModel->subtitle));
            $soundRecording->appendChild($title);

            if (!is_null($recordingModel->version)) {
                $versionType = $document->createElement('VersionType', 'UserDefined');
                $versionType->setAttribute('UserDefinedValue', $recordingModel->version);
                $soundRecording->appendChild($versionType);
            }

            $soundRecording->appendChild($document->createElement('LanguageOfPerformance', $recordingModel->language ? $recordingModel->language->code : 'en'));
            $soundRecording->appendChild($document->createElement('KeySignature', $recordingModel->key_signature));
            $soundRecording->appendChild($document->createElement('TimeSignature', $recordingModel->time_signature));
            $soundRecording->appendChild($document->createElement('Tempo', $recordingModel->tempo));

            $soundRecording->appendChild($document->createElement('Duration', Utilities::formatDuration((int)$recordingModel->duration)));

            $soundRecording->appendChild($document->createElement('Comment', $recordingModel->description));
            $soundRecording->appendChild($document->createElement('SoundRecordingMusicalWorkReference', self::SONG_ID_PREFIX . $recordingModel->song->getKey()));

            $soundRecording = $this->creditList($document, $soundRecording, $recordingModel);

            if (!is_null($recordingModel->created_at)) {
                $dt = Carbon::parse($recordingModel->created_at);
                $soundRecording->appendChild($document->createElement('CreationDate', $dt->toDateString()));
            }

            if (!is_null($recordingModel->mixed_on)) {
                $dt = Carbon::parse($recordingModel->mixed_on);
                $soundRecording->appendChild($document->createElement('MasteredDate', $dt->toDateString()));
            }

            if (!is_null($recordingModel->recorded_on)) {
                $dt = Carbon::parse($recordingModel->recorded_on);
                $soundRecording->appendChild($document->createElement('FirstPublicationDate', $dt->toDateString()));
            }

            $recordingList->appendChild($soundRecording);
        }

        return $recordingList;
    }

    private function songList(DOMDocument $document): DOMElement
    {
        $songList = $document->createElement('MusicalWorkList');

        if ($this->recording) {
            $songModels = [$this->recording->song];
        } else {
            $this->project->load('recordings.song');
            $songModels = $this->project->recordings->pluck('song')->unique();
        }

        foreach ($songModels as $songModel) {
            $musicalWork = $document->createElement('MusicalWork');

            $musicalWorkId = $document->createElement('MusicalWorkId');
            if (!is_null($songModel->iswc)) {
                $musicalWorkId->appendChild($document->createElement('ISWC', $songModel->iswc));
            }
            $musicalWork->appendChild($musicalWorkId);

            $musicalWorkTypeKey = 'Unknown';
            if (!is_null($songModel->type)) {
                $musicalWorkTypeKey = $songModel->type->ddex_key;
            }

            $musicalWork->appendChild($document->createElement('MusicalWorkReference', self::SONG_ID_PREFIX . $songModel->getKey()));

            $title = $document->createElement('Title');
            $title->appendChild($document->createElement('TitleText', $songModel->title));
            $title->appendChild($document->createElement('SubTitle', $songModel->subtitle));
            $musicalWork->appendChild($title);

            $altTitle = $document->createElement('AlternateTitle');
            $altTitle->appendChild($document->createElement('TitleText', $songModel->title_alt));
            $altTitle->appendChild($document->createElement('SubTitle', $songModel->subtitle_alt));
            $musicalWork->appendChild($altTitle);
            $musicalWork->appendChild($document->createElement('CreationDate', Carbon::parse($songModel->created_on)->toDateString()));
            $musicalWork->appendChild($document->createElement('Lyrics', $songModel->lyrics));
            $musicalWork->appendChild($document->createElement('Comment', $songModel->notes));

            $musicalWork->appendChild($document->createElement('MusicalWorkType', $musicalWorkTypeKey));

            $musicalWork = $this->creditList($document, $musicalWork, $songModel);

            $songList->appendChild($musicalWork);
        }

        return $songList;
    }

    private function creditList(DOMDocument $document, DOMElement $parent, Creditable $model): DOMElement
    {
        $credits = $model->credits()
            ->where('contribution_type', $model->getType())
            ->where('contribution_id', $model->getKey())
            ->get();

        foreach ($credits as $credit) {
            $contributorReference = $document->createElement('Contributor');
            $contributorReference->appendChild($document->createElement($model->getContributorReferenceKey(), self::PARTY_ID_PREFIX . $credit->party_id));

            if (!is_null($credit->role)) {
                $contributorRole = $document->createElement('Role', $credit->role->ddex_key);
                if ((bool) $credit->role->user_defined) {
                    $contributorRole->setAttribute('UserDefinedValue', $credit->credit_role_user_defined_value);
                }
                $contributorReference->appendChild($contributorRole);
            }

            if (!is_null($credit->split)) {
                $contributorReference->appendChild($document->createElement('RightSharePercentage', $credit->split));
            }

            if (!is_null($credit->instrument)) {
                $contributorInstrumentType = $document->createElement('InstrumentType', $credit->instrument->ddex_key);
                if ((bool) $credit->instrument->user_defined) {
                    $contributorInstrumentType->setAttribute('UserDefinedValue', $credit->instrument_user_defined_value);
                }
                $contributorReference->appendChild($contributorInstrumentType);
            }

            $parent->appendChild($contributorReference);
        }

        return $parent;
    }

    private function signature(DOMDocument $document): DOMElement
    {
        $signature = $document->createElement('Signature');
        $signedInfo = $document->createElement('SignedInfo');

        $canonicalizationMethod = $document->createElement('ds:CanonicalizationMethod');
        $canonicalizationMethod->setAttribute('Algorithm', '');
        $signatureMethod = $document->createElement('ds:SignatureMethod');
        $signatureMethod->setAttribute('Algorithm', '');

        $reference = $document->createElement('ds:Reference');
        $digestMethod = $document->createElement('ds:DigestMethod');
        $digestMethod->setAttribute('Algorithm', '');
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
