<?php

namespace App\Util\RIN;

use App\Models\Project;
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

    const PROJECT_ID_PREFIX = 'VeVa-Project-';

    /**
     * Import a RIN from an XML document object.
     *
     * @param  SimpleXMLElement $xml
     * @return Importer
     */
    public function fromXML(SimpleXMLElement $xml): Importer
    {
        $fileId = (int) str_replace(self::PROJECT_ID_PREFIX, '', $xml->FileHeader->FileId);

        $project = $xml->ProjectList->Project;

        $parties = $xml->PartyList->children();
        $recordings = $xml->ResourceList->children();
        $sessions = $xml->SessionList->children();
        $songs = $xml->MusicalWorkList->children();

        dump($project);
        dump($parties);
        dump($songs);
        dump($recordings);
        dump($sessions);

        die();

        return $this;
    }
}
