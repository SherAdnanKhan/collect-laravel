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

        $fileHeader = $this->fileHeaderXMl($document, $rin);

        $rin->appendChild($fileHeader);
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

    private function fileHeaderXMl(DOMDocument $document, DOMElement $parent): DOMElement
    {
        $fileHeader = $document->createElement('FileHeader');

        $fileHeader->appendChild($document->createElement('FileId', self::FILE_ID_PREFIX . $this->version));
        $fileHeader->appendChild($document->createElement('FileName', $this->filename()));
        $fileHeader->appendChild($document->createElement('FileCreatedDateTime', Carbon::now()->toIso8601String()));
        $fileHeader->appendChild($document->createElement('FileControlType', App::environment('production') ? 'LiveMessage' : 'TestMessage'));
        $fileHeader->appendChild($document->createElement('SystemType', env('app.name', '')));
        $fileHeader->appendChild($document->createElement('Version', ''));

        return $fileHeader;
    }
}
