<?php

namespace App\Util\RIN;

use App\Models\User;
use SimpleXMLElement;
use DOMDocument;
use DOMElement;

class Exporter
{
    const PROJECT_ID_PREFIX = 'J-';
    const PARTY_ID_PREFIX = 'P-';
    const SESSION_ID_PREFIX = 'O-';
    const SONG_ID_PREFIX = 'W-';
    const RECORDING_ID_PREFIX = 'A-';

    private $currentUser;

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

        $document->appendChild($rin);

        return $document->saveXML();
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
}
