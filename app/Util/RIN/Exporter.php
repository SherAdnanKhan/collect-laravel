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

        $rin->appendChild($fileHeader);
        $rin->appendChild($projectList);
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
