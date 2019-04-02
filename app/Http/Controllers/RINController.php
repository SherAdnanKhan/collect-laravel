<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Util\RIN\Importer;
use \Illuminate\Http\Request;
use SimpleXMLElement;

class RINController extends Controller
{
    /**
     * Handle importing a RIN file into the system.
     *
     * @param  Request $request
     * @return Response
     */
    public function import(Request $request)
    {
        $importer = new Importer();

        $importer->fromXML(new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>
<rin:RecordingInformationNotification xmlns:rin="http://ddex.net/xml/f-rin/10" xmlns:avs="http://ddex.net/xml/avs/avs" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xsi:schemaLocation="http://ddex.net/xml/f-rin/10 http://ddex.net/xml/rin/10/full-recording-information-notification.xsd" SchemaVersionId="f-rin/10" LanguageAndScriptCode="en">
  <FileHeader>
    <FileId>VeVa-Project-3939410</FileId>
    <FileName>8012947A03_rin.xml</FileName>
    <FileCreatedDateTime>2018-09-24T13:58:21-05:00</FileCreatedDateTime>
    <FileControlType>LiveMessage</FileControlType>
    <SystemType/>
    <Version/>
    <FileCreator>
      <PartyId>
        <DPID>PADPIDA2015110204V</DPID>
      </PartyId>
    </FileCreator>
    <CreatedOnBehalfOf>
      <PartyId/>
    </CreatedOnBehalfOf>
    <Signature>
      <SignedInfo>
        <ds:CanonicalizationMethod Algorithm=""/>
        <ds:SignatureMethod Algorithm=""/>
        <ds:Reference>
          <ds:DigestMethod Algorithm=""/>
          <ds:DigestValue/>
        </ds:Reference>
      </SignedInfo>
      <SignatureValue/>
      <KeyInfo>
        <X509Data>
          <X509SubjectName/>
        </X509Data>
      </KeyInfo>
    </Signature>
  </FileHeader>
  <PartyList>
    <Party>
      <PartyId/>
      <PartyReference>P-3790285</PartyReference>
      <PartyName>
        <FullName>Candace Elizabeth Carpenter</FullName>
        <FullNameIndexed>Carpenter, Candace Elizabeth</FullNameIndexed>
        <NamesBeforeKeyName>Candace Elizabeth</NamesBeforeKeyName>
        <KeyName>Carpenter</KeyName>
      </PartyName>
      <IsOrganization>false</IsOrganization>
    </Party>
    <Party>
      <PartyId/>
      <PartyReference>P-2219</PartyReference>
      <PartyName>
        <FullName>The RCA Records Label Nashville</FullName>
        <FullNameIndexed>The RCA Records Label Nashville</FullNameIndexed>
        <KeyName>The RCA Records Label Nashville</KeyName>
      </PartyName>
      <IsOrganization>true</IsOrganization>
    </Party>
    <Party>
      <PartyId/>
      <PartyReference>P-3939407</PartyReference>
      <PartyName>
        <FullName>Fair Shayne</FullName>
        <FullNameIndexed>Shayne, Fair</FullNameIndexed>
        <NamesBeforeKeyName>Fair</NamesBeforeKeyName>
        <KeyName>Shayne</KeyName>
      </PartyName>
      <IsOrganization>false</IsOrganization>
    </Party>
    <Party>
      <PartyId/>
      <PartyReference>P-3585519</PartyReference>
      <PartyName>
        <FullName>Alden Witt</FullName>
        <FullNameIndexed>Witt, Alden</FullNameIndexed>
        <NamesBeforeKeyName>Alden</NamesBeforeKeyName>
        <KeyName>Witt</KeyName>
      </PartyName>
      <IsOrganization>false</IsOrganization>
    </Party>
    <Party>
      <PartyId/>
      <PartyReference>P-3589318</PartyReference>
      <PartyName>
        <FullName>Candi Carpenter</FullName>
        <FullNameIndexed>Carpenter, Candi</FullNameIndexed>
        <NamesBeforeKeyName>Candi</NamesBeforeKeyName>
        <KeyName>Carpenter</KeyName>
      </PartyName>
      <IsOrganization>false</IsOrganization>
    </Party>
    <Party>
      <PartyId/>
      <PartyReference>P-2088398</PartyReference>
      <PartyName>
        <FullName>Bobby Tomberlin</FullName>
        <FullNameIndexed>Tomberlin, Bobby</FullNameIndexed>
        <NamesBeforeKeyName>Bobby</NamesBeforeKeyName>
        <KeyName>Tomberlin</KeyName>
      </PartyName>
      <IsOrganization>false</IsOrganization>
    </Party>
    <Party>
      <PartyId/>
      <PartyReference>P-397987</PartyReference>
      <PartyName>
        <FullName>Phil Everly</FullName>
        <FullNameIndexed>Everly, Phil</FullNameIndexed>
        <NamesBeforeKeyName>Phil</NamesBeforeKeyName>
        <KeyName>Everly</KeyName>
      </PartyName>
      <IsOrganization>false</IsOrganization>
    </Party>
    <Party>
      <PartyId/>
      <PartyReference>P-195346</PartyReference>
      <PartyName>
        <FullName>Mark Narmore</FullName>
        <FullNameIndexed>Narmore, Mark</FullNameIndexed>
        <NamesBeforeKeyName>Mark</NamesBeforeKeyName>
        <KeyName>Narmore</KeyName>
      </PartyName>
      <IsOrganization>false</IsOrganization>
    </Party>
    <Party>
      <PartyId/>
      <PartyReference>P-3939408</PartyReference>
      <PartyName>
        <FullName>Jesse Carl Mueller</FullName>
        <FullNameIndexed>Mueller, Jesse Carl</FullNameIndexed>
        <NamesBeforeKeyName>Jesse Carl</NamesBeforeKeyName>
        <KeyName>Mueller</KeyName>
      </PartyName>
      <IsOrganization>false</IsOrganization>
    </Party>
    <Party>
      <PartyId/>
      <PartyReference>P-110719</PartyReference>
      <PartyName>
        <FullName>Leslie Satcher</FullName>
        <FullNameIndexed>Satcher, Leslie</FullNameIndexed>
        <NamesBeforeKeyName>Leslie</NamesBeforeKeyName>
        <KeyName>Satcher</KeyName>
      </PartyName>
      <IsOrganization>false</IsOrganization>
    </Party>
    <Party>
      <PartyId/>
      <PartyReference>P-3939406</PartyReference>
      <PartyName>
        <FullName>Diamond Rose Bergeron</FullName>
        <FullNameIndexed>Bergeron, Diamond Rose</FullNameIndexed>
        <NamesBeforeKeyName>Diamond Rose</NamesBeforeKeyName>
        <KeyName>Bergeron</KeyName>
      </PartyName>
      <IsOrganization>false</IsOrganization>
    </Party>
    <Party>
      <PartyId/>
      <PartyReference>P-882688</PartyReference>
      <PartyName>
        <FullName>VeVa Sound LLC</FullName>
        <FullNameIndexed>VeVa Sound LLC</FullNameIndexed>
        <KeyName>VeVa Sound LLC</KeyName>
      </PartyName>
      <IsOrganization>true</IsOrganization>
    </Party>
    <Party>
      <PartyId/>
      <PartyReference>P-493107</PartyReference>
      <PartyName>
        <FullName>Chris Taylor</FullName>
        <FullNameIndexed>Taylor, Chris</FullNameIndexed>
        <NamesBeforeKeyName>Chris</NamesBeforeKeyName>
        <KeyName>Taylor</KeyName>
      </PartyName>
      <IsOrganization>false</IsOrganization>
    </Party>
    <Party>
      <PartyId/>
      <PartyReference>P-193474</PartyReference>
      <PartyName>
        <FullName>Gavin Lurssen</FullName>
        <FullNameIndexed>Lurssen, Gavin</FullNameIndexed>
        <NamesBeforeKeyName>Gavin</NamesBeforeKeyName>
        <KeyName>Lurssen</KeyName>
      </PartyName>
      <IsOrganization>false</IsOrganization>
    </Party>
    <Party>
      <PartyId/>
      <PartyReference>P-3939409</PartyReference>
      <PartyName>
        <FullName>Danny Nozell</FullName>
        <FullNameIndexed>Nozell, Danny</FullNameIndexed>
        <NamesBeforeKeyName>Danny</NamesBeforeKeyName>
        <KeyName>Nozell</KeyName>
      </PartyName>
      <IsOrganization>false</IsOrganization>
    </Party>
    <Party>
      <PartyId/>
      <PartyReference>P-193464</PartyReference>
      <PartyName>
        <FullName>Chuck Ainlay</FullName>
        <FullNameIndexed>Ainlay, Chuck</FullNameIndexed>
        <NamesBeforeKeyName>Chuck</NamesBeforeKeyName>
        <KeyName>Ainlay</KeyName>
      </PartyName>
      <IsOrganization>false</IsOrganization>
    </Party>
    <Party>
      <PartyId/>
      <PartyReference>P-193467</PartyReference>
      <PartyName>
        <FullName>Brandon Schexnayder</FullName>
        <FullNameIndexed>Schexnayder, Brandon</FullNameIndexed>
        <NamesBeforeKeyName>Brandon</NamesBeforeKeyName>
        <KeyName>Schexnayder</KeyName>
      </PartyName>
      <IsOrganization>false</IsOrganization>
    </Party>
  </PartyList>
  <MusicalWorkList>
    <MusicalWork>
      <MusicalWorkId/>
      <MusicalWorkReference>W-3956343</MusicalWorkReference>
      <Title>
        <TitleText>Cemetery Dirt</TitleText>
      </Title>
      <MusicalWorkType>OriginalMusicalWork</MusicalWorkType>
    </MusicalWork>
    <MusicalWork>
      <MusicalWorkId/>
      <MusicalWorkReference>W-3939412</MusicalWorkReference>
      <Title>
        <TitleText>Rhythm of the South</TitleText>
      </Title>
      <MusicalWorkType>OriginalMusicalWork</MusicalWorkType>
      <ContributorReference SequenceNumber="1">
        <MusicalWorkContributorReference>P-3939407</MusicalWorkContributorReference>
        <Role>ComposerLyricist</Role>
      </ContributorReference>
      <ContributorReference SequenceNumber="2">
        <MusicalWorkContributorReference>P-3585519</MusicalWorkContributorReference>
        <Role>ComposerLyricist</Role>
      </ContributorReference>
      <ContributorReference SequenceNumber="3">
        <MusicalWorkContributorReference>P-3589318</MusicalWorkContributorReference>
        <Role>ComposerLyricist</Role>
      </ContributorReference>
      <ContributorReference SequenceNumber="4">
        <MusicalWorkContributorReference>P-2088398</MusicalWorkContributorReference>
        <Role>ComposerLyricist</Role>
      </ContributorReference>
      <ContributorReference SequenceNumber="5">
        <MusicalWorkContributorReference>P-397987</MusicalWorkContributorReference>
        <Role>ComposerLyricist</Role>
      </ContributorReference>
      <ContributorReference SequenceNumber="6">
        <MusicalWorkContributorReference>P-195346</MusicalWorkContributorReference>
        <Role>ComposerLyricist</Role>
      </ContributorReference>
    </MusicalWork>
    <MusicalWork>
      <MusicalWorkId/>
      <MusicalWorkReference>W-3939414</MusicalWorkReference>
      <Title>
        <TitleText>The Lie in Believe</TitleText>
      </Title>
      <MusicalWorkType>OriginalMusicalWork</MusicalWorkType>
      <ContributorReference SequenceNumber="1">
        <MusicalWorkContributorReference>P-3939408</MusicalWorkContributorReference>
        <Role>ComposerLyricist</Role>
      </ContributorReference>
      <ContributorReference SequenceNumber="2">
        <MusicalWorkContributorReference>P-3585519</MusicalWorkContributorReference>
        <Role>ComposerLyricist</Role>
      </ContributorReference>
      <ContributorReference SequenceNumber="3">
        <MusicalWorkContributorReference>P-3589318</MusicalWorkContributorReference>
        <Role>ComposerLyricist</Role>
      </ContributorReference>
    </MusicalWork>
    <MusicalWork>
      <MusicalWorkId/>
      <MusicalWorkReference>W-3939411</MusicalWorkReference>
      <Title>
        <TitleText>Fancy Floors</TitleText>
      </Title>
      <MusicalWorkType>OriginalMusicalWork</MusicalWorkType>
      <ContributorReference SequenceNumber="1">
        <MusicalWorkContributorReference>P-3589318</MusicalWorkContributorReference>
        <Role>ComposerLyricist</Role>
      </ContributorReference>
      <ContributorReference SequenceNumber="2">
        <MusicalWorkContributorReference>P-110719</MusicalWorkContributorReference>
        <Role>ComposerLyricist</Role>
      </ContributorReference>
    </MusicalWork>
    <MusicalWork>
      <MusicalWorkId/>
      <MusicalWorkReference>W-3939413</MusicalWorkReference>
      <Title>
        <TitleText>Shoulda Dug Deeper</TitleText>
      </Title>
      <MusicalWorkType>OriginalMusicalWork</MusicalWorkType>
      <ContributorReference SequenceNumber="1">
        <MusicalWorkContributorReference>P-3939406</MusicalWorkContributorReference>
        <Role>ComposerLyricist</Role>
      </ContributorReference>
      <ContributorReference SequenceNumber="2">
        <MusicalWorkContributorReference>P-3585519</MusicalWorkContributorReference>
        <Role>ComposerLyricist</Role>
      </ContributorReference>
      <ContributorReference SequenceNumber="3">
        <MusicalWorkContributorReference>P-3589318</MusicalWorkContributorReference>
        <Role>ComposerLyricist</Role>
      </ContributorReference>
    </MusicalWork>
  </MusicalWorkList>
  <ResourceList>
    <SoundRecording>
      <SoundRecordingType>MusicalWorkSoundRecording</SoundRecordingType>
      <MainArtist>P-3790285</MainArtist>
      <SoundRecordingId>
        <ISRC>USRN11700036</ISRC>
      </SoundRecordingId>
      <ResourceReference>A-3939415</ResourceReference>
      <Title>
        <TitleText>Fancy Floors</TitleText>
      </Title>
      <Version/>
      <LanguageOfPerformance>en</LanguageOfPerformance>
      <KeySignature/>
      <TimeSignature/>
      <Tempo/>
      <Comment>Produced by Chuck Ainlay&#13;From The RCA Records Label Nashville release," ",82876- -
Keys stem has drum bleed, bleed is in multitrack. Use stem "CC_FancyFloors_Stem_LdVoc_02" during archival. Use vocal stems as Aca\'s.</Comment>
      <SoundRecordingMusicalWorkReference>W-3939411</SoundRecordingMusicalWorkReference>
      <ContributorReference>
        <SoundRecordingContributorReference>P-882688</SoundRecordingContributorReference>
        <Role>TransfersAndSafetiesEngineer</Role>
      </ContributorReference>
      <ContributorReference>
        <SoundRecordingContributorReference>P-493107</SoundRecordingContributorReference>
        <Role>MixingSecondEngineer</Role>
      </ContributorReference>
      <ContributorReference>
        <SoundRecordingContributorReference>P-493107</SoundRecordingContributorReference>
        <Role>TrackingSecondEngineer</Role>
      </ContributorReference>
      <ContributorReference>
        <SoundRecordingContributorReference>P-193474</SoundRecordingContributorReference>
        <Role>MasteringEngineer</Role>
      </ContributorReference>
      <ContributorReference>
        <SoundRecordingContributorReference>P-3939409</SoundRecordingContributorReference>
        <Role>ExecutiveProducer</Role>
      </ContributorReference>
      <ContributorReference>
        <SoundRecordingContributorReference>P-193464</SoundRecordingContributorReference>
        <Role>MixingEngineer</Role>
      </ContributorReference>
      <ContributorReference>
        <SoundRecordingContributorReference>P-193464</SoundRecordingContributorReference>
        <Role>OverdubEngineer</Role>
      </ContributorReference>
      <ContributorReference>
        <SoundRecordingContributorReference>P-193464</SoundRecordingContributorReference>
        <Role>TrackingEngineer</Role>
      </ContributorReference>
      <ContributorReference>
        <SoundRecordingContributorReference>P-193464</SoundRecordingContributorReference>
        <Role>RemixingEngineer</Role>
      </ContributorReference>
      <ContributorReference>
        <SoundRecordingContributorReference>P-193464</SoundRecordingContributorReference>
        <Role>StudioProducer</Role>
      </ContributorReference>
      <SoundRecordingSessionReference>O-4005156</SoundRecordingSessionReference>
      <SoundRecordingSessionReference>O-4005154</SoundRecordingSessionReference>
      <SoundRecordingSessionReference>O-3939451</SoundRecordingSessionReference>
      <SoundRecordingSessionReference>O-3939449</SoundRecordingSessionReference>
      <SoundRecordingSessionReference>O-3939447</SoundRecordingSessionReference>
      <SoundRecordingSessionReference>O-3939426</SoundRecordingSessionReference>
      <SoundRecordingSessionReference>O-3939425</SoundRecordingSessionReference>
      <SoundRecordingSessionReference>O-3939424</SoundRecordingSessionReference>
      <SoundRecordingSessionReference>O-3939423</SoundRecordingSessionReference>
      <CreationDate>2017-02-10</CreationDate>
    </SoundRecording>
    <SoundRecording>
      <SoundRecordingType>MusicalWorkSoundRecording</SoundRecordingType>
      <MainArtist>P-3790285</MainArtist>
      <SoundRecordingId>
        <ISRC>USRN11600964</ISRC>
      </SoundRecordingId>
      <ResourceReference>A-3939417</ResourceReference>
      <Title>
        <TitleText>Rhythm of the South</TitleText>
      </Title>
      <Version/>
      <LanguageOfPerformance>en</LanguageOfPerformance>
      <KeySignature/>
      <TimeSignature/>
      <Tempo/>
      <Comment>Produced by Chuck Ainlay&#13;From The RCA Records Label Nashville release," ",82876- -
Vocal stems delivered via FTP by Bradnon Schexnayder (bmschexnayder@gmail.com) through VeVa Sound portal. Use vocal stems as Aca\'s.</Comment>
      <SoundRecordingMusicalWorkReference>W-3939412</SoundRecordingMusicalWorkReference>
      <ContributorReference>
        <SoundRecordingContributorReference>P-882688</SoundRecordingContributorReference>
        <Role>TransfersAndSafetiesEngineer</Role>
      </ContributorReference>
      <ContributorReference>
        <SoundRecordingContributorReference>P-493107</SoundRecordingContributorReference>
        <Role>TrackingSecondEngineer</Role>
      </ContributorReference>
      <ContributorReference>
        <SoundRecordingContributorReference>P-193467</SoundRecordingContributorReference>
        <Role>OverdubSecondEngineer</Role>
      </ContributorReference>
      <ContributorReference>
        <SoundRecordingContributorReference>P-193467</SoundRecordingContributorReference>
        <Role>MixingSecondEngineer</Role>
      </ContributorReference>
      <ContributorReference>
        <SoundRecordingContributorReference>P-193474</SoundRecordingContributorReference>
        <Role>MasteringEngineer</Role>
      </ContributorReference>
      <ContributorReference>
        <SoundRecordingContributorReference>P-193464</SoundRecordingContributorReference>
        <Role>RemixingEngineer</Role>
      </ContributorReference>
      <ContributorReference>
        <SoundRecordingContributorReference>P-193464</SoundRecordingContributorReference>
        <Role>MixingEngineer</Role>
      </ContributorReference>
      <ContributorReference>
        <SoundRecordingContributorReference>P-193464</SoundRecordingContributorReference>
        <Role>OverdubEngineer</Role>
      </ContributorReference>
      <ContributorReference>
        <SoundRecordingContributorReference>P-193464</SoundRecordingContributorReference>
        <Role>DigitalEditingEngineer</Role>
      </ContributorReference>
      <ContributorReference>
        <SoundRecordingContributorReference>P-193464</SoundRecordingContributorReference>
        <Role>ArtistVocalEngineer</Role>
      </ContributorReference>
      <ContributorReference>
        <SoundRecordingContributorReference>P-193464</SoundRecordingContributorReference>
        <Role>TrackingEngineer</Role>
      </ContributorReference>
      <ContributorReference>
        <SoundRecordingContributorReference>P-193464</SoundRecordingContributorReference>
        <Role>StudioProducer</Role>
      </ContributorReference>
      <SoundRecordingSessionReference>O-4005178</SoundRecordingSessionReference>
      <SoundRecordingSessionReference>O-4005175</SoundRecordingSessionReference>
      <SoundRecordingSessionReference>O-3939455</SoundRecordingSessionReference>
      <SoundRecordingSessionReference>O-3939453</SoundRecordingSessionReference>
      <SoundRecordingSessionReference>O-3939431</SoundRecordingSessionReference>
      <SoundRecordingSessionReference>O-3939430</SoundRecordingSessionReference>
      <SoundRecordingSessionReference>O-3939429</SoundRecordingSessionReference>
      <SoundRecordingSessionReference>O-3939427</SoundRecordingSessionReference>
      <CreationDate>2016-10-31</CreationDate>
    </SoundRecording>
    <SoundRecording>
      <SoundRecordingType>MusicalWorkSoundRecording</SoundRecordingType>
      <MainArtist>P-3790285</MainArtist>
      <SoundRecordingId>
        <ISRC>USRN11600963</ISRC>
      </SoundRecordingId>
      <ResourceReference>A-3939419</ResourceReference>
      <Title>
        <TitleText>Shoulda Dug Deeper</TitleText>
      </Title>
      <Version/>
      <LanguageOfPerformance>en</LanguageOfPerformance>
      <KeySignature/>
      <TimeSignature/>
      <Tempo/>
      <Comment>Produced by &#13;From The RCA Records Label Nashville release," ",82876- -
Use vocal stems for Aca\'s.</Comment>
      <SoundRecordingMusicalWorkReference>W-3939413</SoundRecordingMusicalWorkReference>
      <ContributorReference>
        <SoundRecordingContributorReference>P-882688</SoundRecordingContributorReference>
        <Role>TransfersAndSafetiesEngineer</Role>
      </ContributorReference>
      <ContributorReference>
        <SoundRecordingContributorReference>P-193467</SoundRecordingContributorReference>
        <Role>OverdubSecondEngineer</Role>
      </ContributorReference>
      <ContributorReference>
        <SoundRecordingContributorReference>P-193467</SoundRecordingContributorReference>
        <Role>MixingSecondEngineer</Role>
      </ContributorReference>
      <ContributorReference>
        <SoundRecordingContributorReference>P-193474</SoundRecordingContributorReference>
        <Role>MasteringEngineer</Role>
      </ContributorReference>
      <ContributorReference>
        <SoundRecordingContributorReference>P-193464</SoundRecordingContributorReference>
        <Role>RemixingEngineer</Role>
      </ContributorReference>
      <ContributorReference>
        <SoundRecordingContributorReference>P-193464</SoundRecordingContributorReference>
        <Role>MixingEngineer</Role>
      </ContributorReference>
      <ContributorReference>
        <SoundRecordingContributorReference>P-193464</SoundRecordingContributorReference>
        <Role>OverdubEngineer</Role>
      </ContributorReference>
      <ContributorReference>
        <SoundRecordingContributorReference>P-193464</SoundRecordingContributorReference>
        <Role>DigitalEditingEngineer</Role>
      </ContributorReference>
      <ContributorReference>
        <SoundRecordingContributorReference>P-193464</SoundRecordingContributorReference>
        <Role>ArtistVocalEngineer</Role>
      </ContributorReference>
      <ContributorReference>
        <SoundRecordingContributorReference>P-193464</SoundRecordingContributorReference>
        <Role>TrackingEngineer</Role>
      </ContributorReference>
      <ContributorReference>
        <SoundRecordingContributorReference>P-193464</SoundRecordingContributorReference>
        <Role>StudioProducer</Role>
      </ContributorReference>
      <SoundRecordingSessionReference>O-4005188</SoundRecordingSessionReference>
      <SoundRecordingSessionReference>O-4005185</SoundRecordingSessionReference>
      <SoundRecordingSessionReference>O-4005181</SoundRecordingSessionReference>
      <SoundRecordingSessionReference>O-3939465</SoundRecordingSessionReference>
      <SoundRecordingSessionReference>O-3939462</SoundRecordingSessionReference>
      <SoundRecordingSessionReference>O-3939436</SoundRecordingSessionReference>
      <SoundRecordingSessionReference>O-3939435</SoundRecordingSessionReference>
      <SoundRecordingSessionReference>O-3939434</SoundRecordingSessionReference>
      <CreationDate>2016-10-31</CreationDate>
    </SoundRecording>
    <SoundRecording>
      <SoundRecordingType>MusicalWorkSoundRecording</SoundRecordingType>
      <MainArtist>P-3790285</MainArtist>
      <SoundRecordingId>
        <ISRC>USRN11700037</ISRC>
      </SoundRecordingId>
      <ResourceReference>A-3939421</ResourceReference>
      <Title>
        <TitleText>The Lie in Believe</TitleText>
      </Title>
      <Version/>
      <LanguageOfPerformance>en</LanguageOfPerformance>
      <KeySignature/>
      <TimeSignature/>
      <Tempo/>
      <Comment>Produced by &#13;From The RCA Records Label Nashville release," ",82876- -
Use vocal stems for Aca\'s. Drums, keys, and acoustic guitar stem have bleed, bleed is in multitrack, approved as per Mindi McCormick</Comment>
      <SoundRecordingMusicalWorkReference>W-3939414</SoundRecordingMusicalWorkReference>
      <ContributorReference>
        <SoundRecordingContributorReference>P-882688</SoundRecordingContributorReference>
        <Role>TransfersAndSafetiesEngineer</Role>
      </ContributorReference>
      <ContributorReference>
        <SoundRecordingContributorReference>P-493107</SoundRecordingContributorReference>
        <Role>TrackingSecondEngineer</Role>
      </ContributorReference>
      <ContributorReference>
        <SoundRecordingContributorReference>P-193474</SoundRecordingContributorReference>
        <Role>MasteringEngineer</Role>
      </ContributorReference>
      <ContributorReference>
        <SoundRecordingContributorReference>P-3939409</SoundRecordingContributorReference>
        <Role>ExecutiveProducer</Role>
      </ContributorReference>
      <ContributorReference>
        <SoundRecordingContributorReference>P-193464</SoundRecordingContributorReference>
        <Role>MixingEngineer</Role>
      </ContributorReference>
      <ContributorReference>
        <SoundRecordingContributorReference>P-193464</SoundRecordingContributorReference>
        <Role>OverdubEngineer</Role>
      </ContributorReference>
      <ContributorReference>
        <SoundRecordingContributorReference>P-193464</SoundRecordingContributorReference>
        <Role>TrackingEngineer</Role>
      </ContributorReference>
      <ContributorReference>
        <SoundRecordingContributorReference>P-193464</SoundRecordingContributorReference>
        <Role>RemixingEngineer</Role>
      </ContributorReference>
      <ContributorReference>
        <SoundRecordingContributorReference>P-193464</SoundRecordingContributorReference>
        <Role>StudioProducer</Role>
      </ContributorReference>
      <SoundRecordingSessionReference>O-3939471</SoundRecordingSessionReference>
      <SoundRecordingSessionReference>O-3939469</SoundRecordingSessionReference>
      <SoundRecordingSessionReference>O-3939467</SoundRecordingSessionReference>
      <SoundRecordingSessionReference>O-3939442</SoundRecordingSessionReference>
      <SoundRecordingSessionReference>O-3939441</SoundRecordingSessionReference>
      <SoundRecordingSessionReference>O-3939440</SoundRecordingSessionReference>
      <SoundRecordingSessionReference>O-3939439</SoundRecordingSessionReference>
      <SoundRecordingSessionReference>O-3939437</SoundRecordingSessionReference>
      <CreationDate>2017-02-10</CreationDate>
    </SoundRecording>
    <SoundRecording>
      <SoundRecordingType>MusicalWorkSoundRecording</SoundRecordingType>
      <MainArtist>P-3790285</MainArtist>
      <SoundRecordingId>
        <ISRC/>
      </SoundRecordingId>
      <ResourceReference>A-3956344</ResourceReference>
      <Title>
        <TitleText>Cemetery Dirt</TitleText>
      </Title>
      <Version/>
      <LanguageOfPerformance>en</LanguageOfPerformance>
      <KeySignature/>
      <TimeSignature/>
      <Tempo/>
      <Comment>All assets are N/A per Mindi McCormick.</Comment>
      <SoundRecordingMusicalWorkReference>W-3956343</SoundRecordingMusicalWorkReference>
      <ContributorReference>
        <SoundRecordingContributorReference>P-882688</SoundRecordingContributorReference>
        <Role>TransfersAndSafetiesEngineer</Role>
      </ContributorReference>
      <ContributorReference>
        <SoundRecordingContributorReference>P-493107</SoundRecordingContributorReference>
        <Role>TrackingSecondEngineer</Role>
      </ContributorReference>
      <ContributorReference>
        <SoundRecordingContributorReference>P-193474</SoundRecordingContributorReference>
        <Role>MasteringEngineer</Role>
      </ContributorReference>
      <ContributorReference>
        <SoundRecordingContributorReference>P-3939409</SoundRecordingContributorReference>
        <Role>ExecutiveProducer</Role>
      </ContributorReference>
      <ContributorReference>
        <SoundRecordingContributorReference>P-193464</SoundRecordingContributorReference>
        <Role>MixingEngineer</Role>
      </ContributorReference>
      <ContributorReference>
        <SoundRecordingContributorReference>P-193464</SoundRecordingContributorReference>
        <Role>OverdubEngineer</Role>
      </ContributorReference>
      <ContributorReference>
        <SoundRecordingContributorReference>P-193464</SoundRecordingContributorReference>
        <Role>TrackingEngineer</Role>
      </ContributorReference>
      <ContributorReference>
        <SoundRecordingContributorReference>P-193464</SoundRecordingContributorReference>
        <Role>RemixingEngineer</Role>
      </ContributorReference>
      <ContributorReference>
        <SoundRecordingContributorReference>P-193464</SoundRecordingContributorReference>
        <Role>StudioProducer</Role>
      </ContributorReference>
      <SoundRecordingSessionReference>O-4005219</SoundRecordingSessionReference>
      <SoundRecordingSessionReference>O-4005217</SoundRecordingSessionReference>
      <SoundRecordingSessionReference>O-4005215</SoundRecordingSessionReference>
      <SoundRecordingSessionReference>O-4005213</SoundRecordingSessionReference>
      <SoundRecordingSessionReference>O-4005209</SoundRecordingSessionReference>
      <SoundRecordingSessionReference>O-4005205</SoundRecordingSessionReference>
      <SoundRecordingSessionReference>O-4005202</SoundRecordingSessionReference>
    </SoundRecording>
  </ResourceList>
  <ProjectList>
    <Project>
      <ProjectId>
        <ProprietaryId Namespace="Project Number">8012947A03</ProprietaryId>
      </ProjectId>
      <ProjectReference>J-3939410</ProjectReference>
      <MainArtist>P-3790285</MainArtist>
      <ProjectArtist>Candace Elizabeth Carpenter</ProjectArtist>
      <Title>LP #1 - Chuck Ainlay Sides</Title>
      <Label>P-2219</Label>
      <CreationDate>2017-06-13</CreationDate>
      <Status>Verified</Status>
      <Comment>Engineer Documentation Included In Archive</Comment>
    </Project>
  </ProjectList>
  <SessionList>
    <Session>
      <SessionReference>O-3939465</SessionReference>
      <SessionType>Overdub</SessionType>
      <VenueName>Black River Entertainment LLC</VenueName>
      <VenueAddress/>
      <TerritoryCode>US</TerritoryCode>
      <VenueRoom>Sound Stage</VenueRoom>
      <IsUnionSession>false</IsUnionSession>
      <IsAnalogSession>false</IsAnalogSession>
      <Comment/>
      <SessionSoundRecordingReference>A-3939419</SessionSoundRecordingReference>
    </Session>
    <Session>
      <SessionReference>O-4005215</SessionReference>
      <SessionType>Tracking</SessionType>
      <VenueName>Southern Ground Artists Inc</VenueName>
      <VenueAddress/>
      <TerritoryCode>US</TerritoryCode>
      <VenueRoom>Southern Ground</VenueRoom>
      <IsUnionSession>false</IsUnionSession>
      <IsAnalogSession>false</IsAnalogSession>
      <Comment/>
      <SessionSoundRecordingReference>A-3956344</SessionSoundRecordingReference>
    </Session>
    <Session>
      <SessionReference>O-4005202</SessionReference>
      <SessionType>Overdub</SessionType>
      <VenueName>Black River Entertainment LLC</VenueName>
      <VenueAddress/>
      <TerritoryCode>US</TerritoryCode>
      <VenueRoom>Sound Stage</VenueRoom>
      <IsUnionSession>false</IsUnionSession>
      <IsAnalogSession>false</IsAnalogSession>
      <Comment/>
      <SessionSoundRecordingReference>A-3956344</SessionSoundRecordingReference>
    </Session>
    <Session>
      <SessionReference>O-3939426</SessionReference>
      <SessionType>TransfersAndSafeties</SessionType>
      <VenueName>VeVa Sound LLC</VenueName>
      <VenueAddress/>
      <TerritoryCode>US</TerritoryCode>
      <VenueRoom>VeVa Sound</VenueRoom>
      <IsUnionSession>false</IsUnionSession>
      <IsAnalogSession>false</IsAnalogSession>
      <Comment/>
      <SessionSoundRecordingReference>A-3939415</SessionSoundRecordingReference>
    </Session>
    <Session>
      <SessionReference>O-4005217</SessionReference>
      <SessionType>Overdub</SessionType>
      <VenueName>Southern Ground Artists Inc</VenueName>
      <VenueAddress/>
      <TerritoryCode>US</TerritoryCode>
      <VenueRoom>Southern Ground</VenueRoom>
      <IsUnionSession>false</IsUnionSession>
      <IsAnalogSession>false</IsAnalogSession>
      <Comment/>
      <SessionSoundRecordingReference>A-3956344</SessionSoundRecordingReference>
    </Session>
    <Session>
      <SessionReference>O-4005178</SessionReference>
      <SessionType>Mixing</SessionType>
      <VenueName>Black River Entertainment LLC</VenueName>
      <VenueAddress/>
      <TerritoryCode>US</TerritoryCode>
      <VenueRoom>Sound Stage</VenueRoom>
      <IsUnionSession>false</IsUnionSession>
      <IsAnalogSession>false</IsAnalogSession>
      <Comment/>
      <SessionSoundRecordingReference>A-3939417</SessionSoundRecordingReference>
    </Session>
    <Session>
      <SessionReference>O-3939425</SessionReference>
      <SessionType>Overdub</SessionType>
      <VenueName>Southern Ground Artists Inc</VenueName>
      <VenueAddress/>
      <TerritoryCode>US</TerritoryCode>
      <VenueRoom>Southern Ground</VenueRoom>
      <IsUnionSession>false</IsUnionSession>
      <IsAnalogSession>false</IsAnalogSession>
      <Comment/>
      <SessionSoundRecordingReference>A-3939415</SessionSoundRecordingReference>
    </Session>
    <Session>
      <SessionReference>O-3939434</SessionReference>
      <SessionType>Mastering</SessionType>
      <VenueName>Lurssen Mastering, Inc.</VenueName>
      <VenueAddress/>
      <TerritoryCode>US</TerritoryCode>
      <VenueRoom>Lurssen Mastering</VenueRoom>
      <IsUnionSession>false</IsUnionSession>
      <IsAnalogSession>false</IsAnalogSession>
      <Comment/>
      <SessionSoundRecordingReference>A-3939419</SessionSoundRecordingReference>
    </Session>
    <Session>
      <SessionReference>O-4005219</SessionReference>
      <SessionType>TransfersAndSafeties</SessionType>
      <VenueName>VeVa Sound LLC</VenueName>
      <VenueAddress/>
      <TerritoryCode>US</TerritoryCode>
      <VenueRoom>VeVa Sound</VenueRoom>
      <IsUnionSession>false</IsUnionSession>
      <IsAnalogSession>false</IsAnalogSession>
      <Comment/>
      <SessionSoundRecordingReference>A-3956344</SessionSoundRecordingReference>
    </Session>
    <Session>
      <SessionReference>O-3939451</SessionReference>
      <SessionType>Overdub</SessionType>
      <VenueName>Frampton, Peter dba Phenix Stu</VenueName>
      <VenueAddress/>
      <TerritoryCode>US</TerritoryCode>
      <VenueRoom>Studio Phenix</VenueRoom>
      <IsUnionSession>false</IsUnionSession>
      <IsAnalogSession>false</IsAnalogSession>
      <Comment/>
      <SessionSoundRecordingReference>A-3939415</SessionSoundRecordingReference>
    </Session>
    <Session>
      <SessionReference>O-3939439</SessionReference>
      <SessionType>Mastering</SessionType>
      <VenueName>Lurssen Mastering, Inc.</VenueName>
      <VenueAddress/>
      <TerritoryCode>US</TerritoryCode>
      <VenueRoom>Lurssen Mastering</VenueRoom>
      <IsUnionSession>false</IsUnionSession>
      <IsAnalogSession>false</IsAnalogSession>
      <Comment/>
      <SessionSoundRecordingReference>A-3939421</SessionSoundRecordingReference>
    </Session>
    <Session>
      <SessionReference>O-3939447</SessionReference>
      <SessionType>Remix</SessionType>
      <VenueName>Black River Entertainment LLC</VenueName>
      <VenueAddress/>
      <TerritoryCode>US</TerritoryCode>
      <VenueRoom>Sound Stage</VenueRoom>
      <IsUnionSession>false</IsUnionSession>
      <IsAnalogSession>false</IsAnalogSession>
      <Comment/>
      <SessionSoundRecordingReference>A-3939415</SessionSoundRecordingReference>
    </Session>
    <Session>
      <SessionReference>O-4005156</SessionReference>
      <SessionType>Overdub</SessionType>
      <VenueName>Black River Entertainment LLC</VenueName>
      <VenueAddress/>
      <TerritoryCode>US</TerritoryCode>
      <VenueRoom>Sound Stage</VenueRoom>
      <IsUnionSession>false</IsUnionSession>
      <IsAnalogSession>false</IsAnalogSession>
      <Comment/>
      <SessionSoundRecordingReference>A-3939415</SessionSoundRecordingReference>
    </Session>
    <Session>
      <SessionReference>O-3939424</SessionReference>
      <SessionType>Tracking</SessionType>
      <VenueName>Southern Ground Artists Inc</VenueName>
      <VenueAddress/>
      <TerritoryCode>US</TerritoryCode>
      <VenueRoom>Southern Ground</VenueRoom>
      <IsUnionSession>false</IsUnionSession>
      <IsAnalogSession>false</IsAnalogSession>
      <Comment/>
      <SessionSoundRecordingReference>A-3939415</SessionSoundRecordingReference>
    </Session>
    <Session>
      <SessionReference>O-3939435</SessionReference>
      <SessionType>Tracking</SessionType>
      <VenueName>Southern Ground Artists Inc</VenueName>
      <VenueAddress/>
      <TerritoryCode>US</TerritoryCode>
      <VenueRoom>Southern Ground</VenueRoom>
      <IsUnionSession>false</IsUnionSession>
      <IsAnalogSession>false</IsAnalogSession>
      <Comment/>
      <SessionSoundRecordingReference>A-3939419</SessionSoundRecordingReference>
    </Session>
    <Session>
      <SessionReference>O-3939442</SessionReference>
      <SessionType>TransfersAndSafeties</SessionType>
      <VenueName>VeVa Sound LLC</VenueName>
      <VenueAddress/>
      <TerritoryCode>US</TerritoryCode>
      <VenueRoom>VeVa Sound</VenueRoom>
      <IsUnionSession>false</IsUnionSession>
      <IsAnalogSession>false</IsAnalogSession>
      <Comment/>
      <SessionSoundRecordingReference>A-3939421</SessionSoundRecordingReference>
    </Session>
    <Session>
      <SessionReference>O-3939429</SessionReference>
      <SessionType>Mastering</SessionType>
      <VenueName>Lurssen Mastering, Inc.</VenueName>
      <VenueAddress/>
      <TerritoryCode>US</TerritoryCode>
      <VenueRoom>Lurssen Mastering</VenueRoom>
      <IsUnionSession>false</IsUnionSession>
      <IsAnalogSession>false</IsAnalogSession>
      <Comment/>
      <SessionSoundRecordingReference>A-3939417</SessionSoundRecordingReference>
    </Session>
    <Session>
      <SessionReference>O-4005213</SessionReference>
      <SessionType>Mastering</SessionType>
      <VenueName>Lurssen Mastering, Inc.</VenueName>
      <VenueAddress/>
      <TerritoryCode>US</TerritoryCode>
      <VenueRoom>Lurssen Mastering</VenueRoom>
      <IsUnionSession>false</IsUnionSession>
      <IsAnalogSession>false</IsAnalogSession>
      <Comment/>
      <SessionSoundRecordingReference>A-3956344</SessionSoundRecordingReference>
    </Session>
    <Session>
      <SessionReference>O-3939436</SessionReference>
      <SessionType>TransfersAndSafeties</SessionType>
      <VenueName>VeVa Sound LLC</VenueName>
      <VenueAddress/>
      <TerritoryCode>US</TerritoryCode>
      <VenueRoom>VeVa Sound</VenueRoom>
      <IsUnionSession>false</IsUnionSession>
      <IsAnalogSession>false</IsAnalogSession>
      <Comment/>
      <SessionSoundRecordingReference>A-3939419</SessionSoundRecordingReference>
    </Session>
    <Session>
      <SessionReference>O-4005185</SessionReference>
      <SessionType>ArtistVocals</SessionType>
      <VenueName>Black River Entertainment LLC</VenueName>
      <VenueAddress/>
      <TerritoryCode>US</TerritoryCode>
      <VenueRoom>Sound Stage</VenueRoom>
      <IsUnionSession>false</IsUnionSession>
      <IsAnalogSession>false</IsAnalogSession>
      <Comment/>
      <SessionSoundRecordingReference>A-3939419</SessionSoundRecordingReference>
    </Session>
    <Session>
      <SessionReference>O-3939440</SessionReference>
      <SessionType>Tracking</SessionType>
      <VenueName>Southern Ground Artists Inc</VenueName>
      <VenueAddress/>
      <TerritoryCode>US</TerritoryCode>
      <VenueRoom>Southern Ground</VenueRoom>
      <IsUnionSession>false</IsUnionSession>
      <IsAnalogSession>false</IsAnalogSession>
      <Comment/>
      <SessionSoundRecordingReference>A-3939421</SessionSoundRecordingReference>
    </Session>
    <Session>
      <SessionReference>O-4005181</SessionReference>
      <SessionType>Editing</SessionType>
      <VenueName>Ainlay, Chuck</VenueName>
      <VenueAddress/>
      <TerritoryCode>US</TerritoryCode>
      <VenueRoom>Casa De Musica</VenueRoom>
      <IsUnionSession>false</IsUnionSession>
      <IsAnalogSession>false</IsAnalogSession>
      <Comment/>
      <SessionSoundRecordingReference>A-3939419</SessionSoundRecordingReference>
    </Session>
    <Session>
      <SessionReference>O-3939430</SessionReference>
      <SessionType>Tracking</SessionType>
      <VenueName>Southern Ground Artists Inc</VenueName>
      <VenueAddress/>
      <TerritoryCode>US</TerritoryCode>
      <VenueRoom>Southern Ground</VenueRoom>
      <IsUnionSession>false</IsUnionSession>
      <IsAnalogSession>false</IsAnalogSession>
      <Comment/>
      <SessionSoundRecordingReference>A-3939417</SessionSoundRecordingReference>
    </Session>
    <Session>
      <SessionReference>O-3939471</SessionReference>
      <SessionType>Mixing</SessionType>
      <VenueName>Black River Entertainment LLC</VenueName>
      <VenueAddress/>
      <TerritoryCode>US</TerritoryCode>
      <VenueRoom>Sound Stage</VenueRoom>
      <IsUnionSession>false</IsUnionSession>
      <IsAnalogSession>false</IsAnalogSession>
      <Comment/>
      <SessionSoundRecordingReference>A-3939421</SessionSoundRecordingReference>
    </Session>
    <Session>
      <SessionReference>O-3939469</SessionReference>
      <SessionType>Remix</SessionType>
      <VenueName>Black River Entertainment LLC</VenueName>
      <VenueAddress/>
      <TerritoryCode>US</TerritoryCode>
      <VenueRoom>Sound Stage</VenueRoom>
      <IsUnionSession>false</IsUnionSession>
      <IsAnalogSession>false</IsAnalogSession>
      <Comment/>
      <SessionSoundRecordingReference>A-3939421</SessionSoundRecordingReference>
    </Session>
    <Session>
      <SessionReference>O-3939423</SessionReference>
      <SessionType>Mastering</SessionType>
      <VenueName>Lurssen Mastering, Inc.</VenueName>
      <VenueAddress/>
      <TerritoryCode>US</TerritoryCode>
      <VenueRoom>Lurssen Mastering</VenueRoom>
      <IsUnionSession>false</IsUnionSession>
      <IsAnalogSession>false</IsAnalogSession>
      <Comment/>
      <SessionSoundRecordingReference>A-3939415</SessionSoundRecordingReference>
    </Session>
    <Session>
      <SessionReference>O-3939453</SessionReference>
      <SessionType>ArtistVocals</SessionType>
      <VenueName>Black River Entertainment LLC</VenueName>
      <VenueAddress/>
      <TerritoryCode>US</TerritoryCode>
      <VenueRoom>Sound Stage</VenueRoom>
      <IsUnionSession>false</IsUnionSession>
      <IsAnalogSession>false</IsAnalogSession>
      <Comment/>
      <SessionSoundRecordingReference>A-3939417</SessionSoundRecordingReference>
    </Session>
    <Session>
      <SessionReference>O-4005188</SessionReference>
      <SessionType>Overdub</SessionType>
      <VenueName>Black River Entertainment LLC</VenueName>
      <VenueAddress/>
      <TerritoryCode>US</TerritoryCode>
      <VenueRoom>Sound Stage</VenueRoom>
      <IsUnionSession>false</IsUnionSession>
      <IsAnalogSession>false</IsAnalogSession>
      <Comment/>
      <SessionSoundRecordingReference>A-3939419</SessionSoundRecordingReference>
    </Session>
    <Session>
      <SessionReference>O-4005205</SessionReference>
      <SessionType>Remix</SessionType>
      <VenueName>Black River Entertainment LLC</VenueName>
      <VenueAddress/>
      <TerritoryCode>US</TerritoryCode>
      <VenueRoom>Sound Stage</VenueRoom>
      <IsUnionSession>false</IsUnionSession>
      <IsAnalogSession>false</IsAnalogSession>
      <Comment/>
      <SessionSoundRecordingReference>A-3956344</SessionSoundRecordingReference>
    </Session>
    <Session>
      <SessionReference>O-3939441</SessionReference>
      <SessionType>Overdub</SessionType>
      <VenueName>Southern Ground Artists Inc</VenueName>
      <VenueAddress/>
      <TerritoryCode>US</TerritoryCode>
      <VenueRoom>Southern Ground</VenueRoom>
      <IsUnionSession>false</IsUnionSession>
      <IsAnalogSession>false</IsAnalogSession>
      <Comment/>
      <SessionSoundRecordingReference>A-3939421</SessionSoundRecordingReference>
    </Session>
    <Session>
      <SessionReference>O-4005154</SessionReference>
      <SessionType>Mixing</SessionType>
      <VenueName>Ainlay, Chuck</VenueName>
      <VenueAddress/>
      <TerritoryCode>US</TerritoryCode>
      <VenueRoom>Casa De Musica</VenueRoom>
      <IsUnionSession>false</IsUnionSession>
      <IsAnalogSession>false</IsAnalogSession>
      <Comment/>
      <SessionSoundRecordingReference>A-3939415</SessionSoundRecordingReference>
    </Session>
    <Session>
      <SessionReference>O-3939455</SessionReference>
      <SessionType>Overdub</SessionType>
      <VenueName>Black River Entertainment LLC</VenueName>
      <VenueAddress/>
      <TerritoryCode>US</TerritoryCode>
      <VenueRoom>Sound Stage</VenueRoom>
      <IsUnionSession>false</IsUnionSession>
      <IsAnalogSession>false</IsAnalogSession>
      <Comment/>
      <SessionSoundRecordingReference>A-3939417</SessionSoundRecordingReference>
    </Session>
    <Session>
      <SessionReference>O-3939427</SessionReference>
      <SessionType>Overdub</SessionType>
      <VenueName>Black River Entertainment LLC</VenueName>
      <VenueAddress/>
      <TerritoryCode>US</TerritoryCode>
      <VenueRoom>Sound Stage</VenueRoom>
      <IsUnionSession>false</IsUnionSession>
      <IsAnalogSession>false</IsAnalogSession>
      <Comment/>
      <SessionSoundRecordingReference>A-3939417</SessionSoundRecordingReference>
    </Session>
    <Session>
      <SessionReference>O-4005209</SessionReference>
      <SessionType>Mixing</SessionType>
      <VenueName>Black River Entertainment LLC</VenueName>
      <VenueAddress/>
      <TerritoryCode>US</TerritoryCode>
      <VenueRoom>Sound Stage</VenueRoom>
      <IsUnionSession>false</IsUnionSession>
      <IsAnalogSession>false</IsAnalogSession>
      <Comment/>
      <SessionSoundRecordingReference>A-3956344</SessionSoundRecordingReference>
    </Session>
    <Session>
      <SessionReference>O-4005175</SessionReference>
      <SessionType>Editing</SessionType>
      <VenueName>Black River Entertainment LLC</VenueName>
      <VenueAddress/>
      <TerritoryCode>US</TerritoryCode>
      <VenueRoom>Sound Stage</VenueRoom>
      <IsUnionSession>false</IsUnionSession>
      <IsAnalogSession>false</IsAnalogSession>
      <Comment/>
      <SessionSoundRecordingReference>A-3939417</SessionSoundRecordingReference>
    </Session>
    <Session>
      <SessionReference>O-3939431</SessionReference>
      <SessionType>TransfersAndSafeties</SessionType>
      <VenueName>VeVa Sound LLC</VenueName>
      <VenueAddress/>
      <TerritoryCode>US</TerritoryCode>
      <VenueRoom>VeVa Sound</VenueRoom>
      <IsUnionSession>false</IsUnionSession>
      <IsAnalogSession>false</IsAnalogSession>
      <Comment/>
      <SessionSoundRecordingReference>A-3939417</SessionSoundRecordingReference>
    </Session>
    <Session>
      <SessionReference>O-3939462</SessionReference>
      <SessionType>Mixing</SessionType>
      <VenueName>Ainlay, Chuck</VenueName>
      <VenueAddress/>
      <TerritoryCode>US</TerritoryCode>
      <VenueRoom>Casa De Musica</VenueRoom>
      <IsUnionSession>false</IsUnionSession>
      <IsAnalogSession>false</IsAnalogSession>
      <Comment/>
      <SessionSoundRecordingReference>A-3939419</SessionSoundRecordingReference>
    </Session>
    <Session>
      <SessionReference>O-3939437</SessionReference>
      <SessionType>Overdub</SessionType>
      <VenueName>Black River Entertainment LLC</VenueName>
      <VenueAddress/>
      <TerritoryCode>US</TerritoryCode>
      <VenueRoom>Sound Stage</VenueRoom>
      <IsUnionSession>false</IsUnionSession>
      <IsAnalogSession>false</IsAnalogSession>
      <Comment/>
      <SessionSoundRecordingReference>A-3939421</SessionSoundRecordingReference>
    </Session>
    <Session>
      <SessionReference>O-3939449</SessionReference>
      <SessionType>Mixing</SessionType>
      <VenueName>Black River Entertainment LLC</VenueName>
      <VenueAddress/>
      <TerritoryCode>US</TerritoryCode>
      <VenueRoom>Sound Stage</VenueRoom>
      <IsUnionSession>false</IsUnionSession>
      <IsAnalogSession>false</IsAnalogSession>
      <Comment/>
      <SessionSoundRecordingReference>A-3939415</SessionSoundRecordingReference>
    </Session>
    <Session>
      <SessionReference>O-3939467</SessionReference>
      <SessionType>Overdub</SessionType>
      <VenueName>Black River Entertainment LLC</VenueName>
      <VenueAddress/>
      <TerritoryCode>US</TerritoryCode>
      <VenueRoom>Sound Stage</VenueRoom>
      <IsUnionSession>false</IsUnionSession>
      <IsAnalogSession>false</IsAnalogSession>
      <Comment/>
      <SessionSoundRecordingReference>A-3939421</SessionSoundRecordingReference>
    </Session>
  </SessionList>
  <DataCarrierList>
    <DataCarrier>
      <DataCarrierId>
        <ProprietaryId Namespace="Barcode ID">4367526</ProprietaryId>
      </DataCarrierId>
      <DataCarrierReference>D-3939473</DataCarrierReference>
      <DataCarrierType>HardDiskDrive_ExternalFirewire/UsbInterface</DataCarrierType>
      <Title>
        <TitleText>Candi Carpenter Mix and Multitrack Master Drive</TitleText>
      </Title>
      <ManufacturerName>Avastor</ManufacturerName>
      <ModelName>HDX 500 GB</ModelName>
      <NumberOfDataCarriersInSet>1</NumberOfDataCarriersInSet>
      <DataCarrierOfSet>1</DataCarrierOfSet>
      <ContentSummary/>
      <CurrentLocation>Sony Music Nashville</CurrentLocation>
      <Comment>Drive A</Comment>
    </DataCarrier>
    <DataCarrier>
      <DataCarrierId>
        <ProprietaryId Namespace="Barcode ID">4367527</ProprietaryId>
      </DataCarrierId>
      <DataCarrierReference>D-3939474</DataCarrierReference>
      <DataCarrierType>HardDiskDrive_ExternalFirewire/UsbInterface</DataCarrierType>
      <Title>
        <TitleText>Candi Carpenter Mix and Multitrack Safety Drive</TitleText>
      </Title>
      <ManufacturerName>Avastor</ManufacturerName>
      <ModelName>HDX 500 GB</ModelName>
      <NumberOfDataCarriersInSet>1</NumberOfDataCarriersInSet>
      <DataCarrierOfSet>1</DataCarrierOfSet>
      <ContentSummary/>
      <CurrentLocation>Sony Music Nashville</CurrentLocation>
      <Comment>Drive B</Comment>
    </DataCarrier>
    <DataCarrier>
      <DataCarrierId>
        <ProprietaryId Namespace="Barcode ID">4367528</ProprietaryId>
      </DataCarrierId>
      <DataCarrierReference>D-3939475</DataCarrierReference>
      <DataCarrierType>MoDisk_1200MB</DataCarrierType>
      <Title>
        <TitleText>Candi Carpenter MO Disk</TitleText>
      </Title>
      <ManufacturerName>Sony</ManufacturerName>
      <ModelName>Magneto Optical Disk EDM-1300</ModelName>
      <NumberOfDataCarriersInSet>1</NumberOfDataCarriersInSet>
      <DataCarrierOfSet>1</DataCarrierOfSet>
      <ContentSummary>Candi Carpenter Backstage SSL 9000j</ContentSummary>
      <CurrentLocation>Sony Music Nashville</CurrentLocation>
    </DataCarrier>
  </DataCarrierList>
  <ElementList>
    <Element>
      <ElementReference>M-3939485</ElementReference>
      <Title>
        <TitleText>Inst</TitleText>
      </Title>
      <IsMultiTrack>false</IsMultiTrack>
      <Designation>Master</Designation>
      <Configuration>InterleavedStereoFiles</Configuration>
      <BitDepth>24</BitDepth>
      <SamplingRate>48000</SamplingRate>
      <FileType>BwfFile</FileType>
      <Comment>CC_IShouldveDugDeeper_R3_Inst.wav</Comment>
      <ElementDataCarrierReference>
        <ElementDataCarrierReference>D-3939473</ElementDataCarrierReference>
        <DataCarrierFormat>HierarchicalFileSystemPlus</DataCarrierFormat>
      </ElementDataCarrierReference>
      <ElementSoundRecordingReference>A-3939419</ElementSoundRecordingReference>
    </Element>
    <Element>
      <ElementReference>M-3939496</ElementReference>
      <Title>
        <TitleText>Mstr No BGV</TitleText>
      </Title>
      <IsMultiTrack>false</IsMultiTrack>
      <Designation>Master</Designation>
      <Configuration>InterleavedStereoFiles</Configuration>
      <BitDepth>24</BitDepth>
      <SamplingRate>96000</SamplingRate>
      <FileType>BwfFile</FileType>
      <Comment>CC_TheLieInBelieve_NoBgVoc_02.wav</Comment>
      <ElementDataCarrierReference>
        <ElementDataCarrierReference>D-3939473</ElementDataCarrierReference>
        <DataCarrierFormat>HierarchicalFileSystemPlus</DataCarrierFormat>
      </ElementDataCarrierReference>
      <ElementSoundRecordingReference>A-3939421</ElementSoundRecordingReference>
    </Element>
    <Element>
      <ElementReference>M-3956364</ElementReference>
      <Title>
        <TitleText>Aca BGV</TitleText>
      </Title>
      <IsMultiTrack>false</IsMultiTrack>
      <Designation>Master</Designation>
      <Configuration>InterleavedStereoFiles</Configuration>
      <BitDepth>24</BitDepth>
      <SamplingRate>96000</SamplingRate>
      <FileType>BwfFile</FileType>
      <Comment>CC_TheLieInBelieve_Stem_BgVoc_02.wav</Comment>
      <ElementDataCarrierReference>
        <ElementDataCarrierReference>D-3939473</ElementDataCarrierReference>
        <DataCarrierFormat>HierarchicalFileSystemPlus</DataCarrierFormat>
      </ElementDataCarrierReference>
      <ElementSoundRecordingReference>A-3939421</ElementSoundRecordingReference>
    </Element>
    <Element>
      <ElementReference>M-3939486</ElementReference>
      <Title>
        <TitleText>Inst</TitleText>
      </Title>
      <IsMultiTrack>false</IsMultiTrack>
      <Designation>Master</Designation>
      <Configuration>InterleavedStereoFiles</Configuration>
      <BitDepth>24</BitDepth>
      <SamplingRate>48000</SamplingRate>
      <FileType>BwfFile</FileType>
      <Comment>CC_RhyOfTheSouth_48k24b_Bwav_R3AltInst.wav</Comment>
      <ElementDataCarrierReference>
        <ElementDataCarrierReference>D-3939473</ElementDataCarrierReference>
        <DataCarrierFormat>HierarchicalFileSystemPlus</DataCarrierFormat>
      </ElementDataCarrierReference>
      <ElementSoundRecordingReference>A-3939417</ElementSoundRecordingReference>
    </Element>
    <Element>
      <ElementReference>M-3939484</ElementReference>
      <Title>
        <TitleText>Inst</TitleText>
      </Title>
      <IsMultiTrack>false</IsMultiTrack>
      <Designation>Master</Designation>
      <Configuration>InterleavedStereoFiles</Configuration>
      <BitDepth>24</BitDepth>
      <SamplingRate>96000</SamplingRate>
      <FileType>BwfFile</FileType>
      <Comment>CC_FancyFloors_Inst_01.wav</Comment>
      <ElementDataCarrierReference>
        <ElementDataCarrierReference>D-3939473</ElementDataCarrierReference>
        <DataCarrierFormat>HierarchicalFileSystemPlus</DataCarrierFormat>
      </ElementDataCarrierReference>
      <ElementSoundRecordingReference>A-3939415</ElementSoundRecordingReference>
    </Element>
    <Element>
      <ElementReference>M-3939490</ElementReference>
      <Title>
        <TitleText>LVU</TitleText>
      </Title>
      <IsMultiTrack>false</IsMultiTrack>
      <Designation>Master</Designation>
      <Configuration>InterleavedStereoFiles</Configuration>
      <BitDepth>24</BitDepth>
      <SamplingRate>48000</SamplingRate>
      <FileType>BwfFile</FileType>
      <Comment>CC_IShouldveDugDeeper_R3_LdVocalUp.wav</Comment>
      <ElementDataCarrierReference>
        <ElementDataCarrierReference>D-3939473</ElementDataCarrierReference>
        <DataCarrierFormat>HierarchicalFileSystemPlus</DataCarrierFormat>
      </ElementDataCarrierReference>
      <ElementSoundRecordingReference>A-3939419</ElementSoundRecordingReference>
    </Element>
    <Element>
      <ElementReference>M-3956363</ElementReference>
      <Title>
        <TitleText>Aca BGV</TitleText>
      </Title>
      <IsMultiTrack>false</IsMultiTrack>
      <Designation>Master</Designation>
      <Configuration>InterleavedStereoFiles</Configuration>
      <BitDepth>24</BitDepth>
      <SamplingRate>48000</SamplingRate>
      <FileType>BwfFile</FileType>
      <Comment>CC_IShouldveDugDeeper_R2_Stem_BgVoc.wav</Comment>
      <ElementDataCarrierReference>
        <ElementDataCarrierReference>D-3939473</ElementDataCarrierReference>
        <DataCarrierFormat>HierarchicalFileSystemPlus</DataCarrierFormat>
      </ElementDataCarrierReference>
      <ElementSoundRecordingReference>A-3939419</ElementSoundRecordingReference>
    </Element>
    <Element>
      <ElementReference>M-3939483</ElementReference>
      <Title>
        <TitleText>BGV Up</TitleText>
      </Title>
      <IsMultiTrack>false</IsMultiTrack>
      <Designation>Master</Designation>
      <Configuration>InterleavedStereoFiles</Configuration>
      <BitDepth>24</BitDepth>
      <SamplingRate>96000</SamplingRate>
      <FileType>BwfFile</FileType>
      <Comment>CCarpenter_TheLieInBelieve_BgVoc_01.wav</Comment>
      <ElementDataCarrierReference>
        <ElementDataCarrierReference>D-3939473</ElementDataCarrierReference>
        <DataCarrierFormat>HierarchicalFileSystemPlus</DataCarrierFormat>
      </ElementDataCarrierReference>
      <ElementSoundRecordingReference>A-3939421</ElementSoundRecordingReference>
    </Element>
    <Element>
      <ElementReference>M-3939476</ElementReference>
      <Title>
        <TitleText>AVU</TitleText>
      </Title>
      <IsMultiTrack>false</IsMultiTrack>
      <Designation>Master</Designation>
      <Configuration>InterleavedStereoFiles</Configuration>
      <BitDepth>24</BitDepth>
      <SamplingRate>96000</SamplingRate>
      <FileType>BwfFile</FileType>
      <Comment>CC_FancyFloors_AllVocUp_01.wav</Comment>
      <ElementDataCarrierReference>
        <ElementDataCarrierReference>D-3939473</ElementDataCarrierReference>
        <DataCarrierFormat>HierarchicalFileSystemPlus</DataCarrierFormat>
      </ElementDataCarrierReference>
      <ElementSoundRecordingReference>A-3939415</ElementSoundRecordingReference>
    </Element>
    <Element>
      <ElementReference>M-4002282</ElementReference>
      <Title>
        <TitleText>Aca BGV</TitleText>
      </Title>
      <IsMultiTrack>false</IsMultiTrack>
      <Designation>Master</Designation>
      <Configuration>InterleavedStereoFiles</Configuration>
      <BitDepth>24</BitDepth>
      <SamplingRate>48000</SamplingRate>
      <FileType>BwfFile</FileType>
      <Comment>CC_RhyOfTheSouth_48k24b_Bwav_R3BgvVocStem.wav</Comment>
      <ElementDataCarrierReference>
        <ElementDataCarrierReference>D-3939473</ElementDataCarrierReference>
        <DataCarrierFormat>HierarchicalFileSystemPlus</DataCarrierFormat>
      </ElementDataCarrierReference>
      <ElementSoundRecordingReference>A-3939417</ElementSoundRecordingReference>
    </Element>
    <Element>
      <ElementReference>M-3939504</ElementReference>
      <Title>
        <TitleText>Stems</TitleText>
      </Title>
      <IsMultiTrack>false</IsMultiTrack>
      <Designation>Master</Designation>
      <Configuration>InterleavedStereoFiles</Configuration>
      <BitDepth>24</BitDepth>
      <SamplingRate>48000</SamplingRate>
      <FileType>BwfFile</FileType>
      <Comment>In Folder - CCarpenter_IShouldveDugDeeper_48k24b_Bwav_R3Mixes_031417</Comment>
      <ElementDataCarrierReference>
        <ElementDataCarrierReference>D-3939473</ElementDataCarrierReference>
        <DataCarrierFormat>HierarchicalFileSystemPlus</DataCarrierFormat>
      </ElementDataCarrierReference>
      <ElementSoundRecordingReference>A-3939419</ElementSoundRecordingReference>
    </Element>
    <Element>
      <ElementReference>M-3939503</ElementReference>
      <Title>
        <TitleText>Stems</TitleText>
      </Title>
      <IsMultiTrack>false</IsMultiTrack>
      <Designation>Master</Designation>
      <Configuration>InterleavedStereoFiles</Configuration>
      <BitDepth>24</BitDepth>
      <SamplingRate>96000</SamplingRate>
      <FileType>BwfFile</FileType>
      <Comment>In Folder - CCarpenter_TheLieInBelieve_96k24b_Bwav_Mixes_030917</Comment>
      <ElementDataCarrierReference>
        <ElementDataCarrierReference>D-3939473</ElementDataCarrierReference>
        <DataCarrierFormat>HierarchicalFileSystemPlus</DataCarrierFormat>
      </ElementDataCarrierReference>
      <ElementSoundRecordingReference>A-3939421</ElementSoundRecordingReference>
    </Element>
    <Element>
      <ElementReference>M-3939494</ElementReference>
      <Title>
        <TitleText>Mstr No BGV</TitleText>
      </Title>
      <IsMultiTrack>false</IsMultiTrack>
      <Designation>Master</Designation>
      <Configuration>InterleavedStereoFiles</Configuration>
      <BitDepth>24</BitDepth>
      <SamplingRate>96000</SamplingRate>
      <FileType>BwfFile</FileType>
      <Comment>CC_FancyFloors_NoBgVoc_01.wav</Comment>
      <ElementDataCarrierReference>
        <ElementDataCarrierReference>D-3939473</ElementDataCarrierReference>
        <DataCarrierFormat>HierarchicalFileSystemPlus</DataCarrierFormat>
      </ElementDataCarrierReference>
      <ElementSoundRecordingReference>A-3939415</ElementSoundRecordingReference>
    </Element>
    <Element>
      <ElementReference>M-3939499</ElementReference>
      <Title>
        <TitleText>Mstr</TitleText>
      </Title>
      <IsMultiTrack>false</IsMultiTrack>
      <Designation>Master</Designation>
      <Configuration>InterleavedStereoFiles</Configuration>
      <BitDepth>24</BitDepth>
      <SamplingRate>96000</SamplingRate>
      <FileType>BwfFile</FileType>
      <Comment>CC_FancyFloors_MixMaster_02.wav</Comment>
      <ElementDataCarrierReference>
        <ElementDataCarrierReference>D-3939473</ElementDataCarrierReference>
        <DataCarrierFormat>HierarchicalFileSystemPlus</DataCarrierFormat>
      </ElementDataCarrierReference>
      <ElementSoundRecordingReference>A-3939415</ElementSoundRecordingReference>
    </Element>
    <Element>
      <ElementReference>M-3939498</ElementReference>
      <Title>
        <TitleText>Mstr No BGV</TitleText>
      </Title>
      <IsMultiTrack>false</IsMultiTrack>
      <Designation>Master</Designation>
      <Configuration>InterleavedStereoFiles</Configuration>
      <BitDepth>24</BitDepth>
      <SamplingRate>48000</SamplingRate>
      <FileType>BwfFile</FileType>
      <Comment>CC_RhyOfTheSouth_48k24b_Bwav_R3AltNoBgVoc.wav</Comment>
      <ElementDataCarrierReference>
        <ElementDataCarrierReference>D-3939473</ElementDataCarrierReference>
        <DataCarrierFormat>HierarchicalFileSystemPlus</DataCarrierFormat>
      </ElementDataCarrierReference>
      <ElementSoundRecordingReference>A-3939417</ElementSoundRecordingReference>
    </Element>
    <Element>
      <ElementReference>M-3956365</ElementReference>
      <Title>
        <TitleText>Aca LV</TitleText>
      </Title>
      <IsMultiTrack>false</IsMultiTrack>
      <Designation>Master</Designation>
      <Configuration>InterleavedStereoFiles</Configuration>
      <BitDepth>24</BitDepth>
      <SamplingRate>96000</SamplingRate>
      <FileType>BwfFile</FileType>
      <Comment>CC_FancyFloors_Stem_LdVoc_02.wav</Comment>
      <ElementDataCarrierReference>
        <ElementDataCarrierReference>D-3939473</ElementDataCarrierReference>
        <DataCarrierFormat>HierarchicalFileSystemPlus</DataCarrierFormat>
      </ElementDataCarrierReference>
      <ElementSoundRecordingReference>A-3939415</ElementSoundRecordingReference>
    </Element>
    <Element>
      <ElementReference>M-4002284</ElementReference>
      <Title>
        <TitleText>Stems</TitleText>
      </Title>
      <IsMultiTrack>false</IsMultiTrack>
      <Designation>Master</Designation>
      <Configuration>InterleavedStereoFiles</Configuration>
      <BitDepth>24</BitDepth>
      <SamplingRate>48000</SamplingRate>
      <FileType>BwfFile</FileType>
      <Comment>In Folder - CCarpenter_RhythmOfTheSouth_48k24b_Bwav_R3Mixes_031617</Comment>
      <ElementDataCarrierReference>
        <ElementDataCarrierReference>D-3939473</ElementDataCarrierReference>
        <DataCarrierFormat>HierarchicalFileSystemPlus</DataCarrierFormat>
      </ElementDataCarrierReference>
      <ElementSoundRecordingReference>A-3939417</ElementSoundRecordingReference>
    </Element>
    <Element>
      <ElementReference>M-3939500</ElementReference>
      <Title>
        <TitleText>Mstr</TitleText>
      </Title>
      <IsMultiTrack>false</IsMultiTrack>
      <Designation>Master</Designation>
      <Configuration>InterleavedStereoFiles</Configuration>
      <BitDepth>24</BitDepth>
      <SamplingRate>96000</SamplingRate>
      <FileType>BwfFile</FileType>
      <Comment>CCarpenter_TheLieInBelieve_MixMa_02.wav</Comment>
      <ElementDataCarrierReference>
        <ElementDataCarrierReference>D-3939473</ElementDataCarrierReference>
        <DataCarrierFormat>HierarchicalFileSystemPlus</DataCarrierFormat>
      </ElementDataCarrierReference>
      <ElementSoundRecordingReference>A-3939421</ElementSoundRecordingReference>
    </Element>
    <Element>
      <ElementReference>M-3939492</ElementReference>
      <Title>
        <TitleText>Mstr 1</TitleText>
      </Title>
      <IsMultiTrack>false</IsMultiTrack>
      <Designation>Master</Designation>
      <Configuration>InterleavedStereoFiles</Configuration>
      <BitDepth>24</BitDepth>
      <SamplingRate>48000</SamplingRate>
      <FileType>BwfFile</FileType>
      <Comment>CC_IShouldveDugDeeper_R3_MstrFromStem.wav</Comment>
      <ElementDataCarrierReference>
        <ElementDataCarrierReference>D-3939473</ElementDataCarrierReference>
        <DataCarrierFormat>HierarchicalFileSystemPlus</DataCarrierFormat>
      </ElementDataCarrierReference>
      <ElementSoundRecordingReference>A-3939419</ElementSoundRecordingReference>
    </Element>
    <Element>
      <ElementReference>M-3939478</ElementReference>
      <Title>
        <TitleText>AVU</TitleText>
      </Title>
      <IsMultiTrack>false</IsMultiTrack>
      <Designation>Master</Designation>
      <Configuration>InterleavedStereoFiles</Configuration>
      <BitDepth>24</BitDepth>
      <SamplingRate>48000</SamplingRate>
      <FileType>BwfFile</FileType>
      <Comment>CC_RhyOfTheSouth_48k24b_Bwav_R3AltAllVocUp.wav</Comment>
      <ElementDataCarrierReference>
        <ElementDataCarrierReference>D-3939473</ElementDataCarrierReference>
        <DataCarrierFormat>HierarchicalFileSystemPlus</DataCarrierFormat>
      </ElementDataCarrierReference>
      <ElementSoundRecordingReference>A-3939417</ElementSoundRecordingReference>
    </Element>
    <Element>
      <ElementReference>M-3939505</ElementReference>
      <Title>
        <TitleText>TV</TitleText>
      </Title>
      <IsMultiTrack>false</IsMultiTrack>
      <Designation>Master</Designation>
      <Configuration>InterleavedStereoFiles</Configuration>
      <BitDepth>24</BitDepth>
      <SamplingRate>96000</SamplingRate>
      <FileType>BwfFile</FileType>
      <Comment>CC_FancyFloors_NoLdVoc_02.wav</Comment>
      <ElementDataCarrierReference>
        <ElementDataCarrierReference>D-3939473</ElementDataCarrierReference>
        <DataCarrierFormat>HierarchicalFileSystemPlus</DataCarrierFormat>
      </ElementDataCarrierReference>
      <ElementSoundRecordingReference>A-3939415</ElementSoundRecordingReference>
    </Element>
    <Element>
      <ElementReference>M-3956362</ElementReference>
      <Title>
        <TitleText>Aca BGV</TitleText>
      </Title>
      <IsMultiTrack>false</IsMultiTrack>
      <Designation>Master</Designation>
      <Configuration>InterleavedStereoFiles</Configuration>
      <BitDepth>24</BitDepth>
      <SamplingRate>96000</SamplingRate>
      <FileType>BwfFile</FileType>
      <Comment>CC_FancyFloors_Stem_BgVoc_01.wav</Comment>
      <ElementDataCarrierReference>
        <ElementDataCarrierReference>D-3939473</ElementDataCarrierReference>
        <DataCarrierFormat>HierarchicalFileSystemPlus</DataCarrierFormat>
      </ElementDataCarrierReference>
      <ElementSoundRecordingReference>A-3939415</ElementSoundRecordingReference>
    </Element>
    <Element>
      <ElementReference>M-3956380</ElementReference>
      <Title>
        <TitleText>2</TitleText>
      </Title>
      <IsMultiTrack>true</IsMultiTrack>
      <Designation>Master</Designation>
      <Configuration>Nuendo</Configuration>
      <BitDepth>24</BitDepth>
      <SamplingRate>48000</SamplingRate>
      <FileType>BwfFile</FileType>
      <Comment>PrintR3 MasterAlt.npr - Multitrack Stems Used To Remix Are In This Session</Comment>
      <ElementDataCarrierReference>
        <ElementDataCarrierReference>D-3939473</ElementDataCarrierReference>
        <DataCarrierFormat>HierarchicalFileSystemPlus</DataCarrierFormat>
      </ElementDataCarrierReference>
      <ElementSoundRecordingReference>A-3939417</ElementSoundRecordingReference>
    </Element>
    <Element>
      <ElementReference>M-3956378</ElementReference>
      <Title>
        <TitleText>2</TitleText>
      </Title>
      <IsMultiTrack>true</IsMultiTrack>
      <Designation>Master</Designation>
      <Configuration>Nuendo</Configuration>
      <BitDepth>24</BitDepth>
      <SamplingRate>48000</SamplingRate>
      <FileType>BwfFile</FileType>
      <Comment>PrintMaster.npr - Inst Stem With New Vocal Overdub</Comment>
      <ElementDataCarrierReference>
        <ElementDataCarrierReference>D-3939473</ElementDataCarrierReference>
        <DataCarrierFormat>HierarchicalFileSystemPlus</DataCarrierFormat>
      </ElementDataCarrierReference>
      <ElementSoundRecordingReference>A-3939419</ElementSoundRecordingReference>
    </Element>
    <Element>
      <ElementReference>M-3939479</ElementReference>
      <Title>
        <TitleText>AVU</TitleText>
      </Title>
      <IsMultiTrack>false</IsMultiTrack>
      <Designation>Master</Designation>
      <Configuration>InterleavedStereoFiles</Configuration>
      <BitDepth>24</BitDepth>
      <SamplingRate>96000</SamplingRate>
      <FileType>BwfFile</FileType>
      <Comment>CCarpenter_TheLieInBelieve_AllVo_01.wav</Comment>
      <ElementDataCarrierReference>
        <ElementDataCarrierReference>D-3939473</ElementDataCarrierReference>
        <DataCarrierFormat>HierarchicalFileSystemPlus</DataCarrierFormat>
      </ElementDataCarrierReference>
      <ElementSoundRecordingReference>A-3939421</ElementSoundRecordingReference>
    </Element>
    <Element>
      <ElementReference>M-3939507</ElementReference>
      <Title>
        <TitleText>TV</TitleText>
      </Title>
      <IsMultiTrack>false</IsMultiTrack>
      <Designation>Master</Designation>
      <Configuration>InterleavedStereoFiles</Configuration>
      <BitDepth>24</BitDepth>
      <SamplingRate>48000</SamplingRate>
      <FileType>BwfFile</FileType>
      <Comment>CC_IShouldveDugDeeper_R3_NoLdVoc.wav</Comment>
      <ElementDataCarrierReference>
        <ElementDataCarrierReference>D-3939473</ElementDataCarrierReference>
        <DataCarrierFormat>HierarchicalFileSystemPlus</DataCarrierFormat>
      </ElementDataCarrierReference>
      <ElementSoundRecordingReference>A-3939419</ElementSoundRecordingReference>
    </Element>
    <Element>
      <ElementReference>M-3939489</ElementReference>
      <Title>
        <TitleText>LVU</TitleText>
      </Title>
      <IsMultiTrack>false</IsMultiTrack>
      <Designation>Master</Designation>
      <Configuration>InterleavedStereoFiles</Configuration>
      <BitDepth>24</BitDepth>
      <SamplingRate>96000</SamplingRate>
      <FileType>BwfFile</FileType>
      <Comment>CCarpenter_TheLieInBelieve_LdVoc_01.wav</Comment>
      <ElementDataCarrierReference>
        <ElementDataCarrierReference>D-3939473</ElementDataCarrierReference>
        <DataCarrierFormat>HierarchicalFileSystemPlus</DataCarrierFormat>
      </ElementDataCarrierReference>
      <ElementSoundRecordingReference>A-3939421</ElementSoundRecordingReference>
    </Element>
    <Element>
      <ElementReference>M-3939477</ElementReference>
      <Title>
        <TitleText>AVU</TitleText>
      </Title>
      <IsMultiTrack>false</IsMultiTrack>
      <Designation>Master</Designation>
      <Configuration>InterleavedStereoFiles</Configuration>
      <BitDepth>24</BitDepth>
      <SamplingRate>48000</SamplingRate>
      <FileType>BwfFile</FileType>
      <Comment>CC_IShouldveDugDeeper_R3_AllVocalUp.wav</Comment>
      <ElementDataCarrierReference>
        <ElementDataCarrierReference>D-3939473</ElementDataCarrierReference>
        <DataCarrierFormat>HierarchicalFileSystemPlus</DataCarrierFormat>
      </ElementDataCarrierReference>
      <ElementSoundRecordingReference>A-3939419</ElementSoundRecordingReference>
    </Element>
    <Element>
      <ElementReference>M-3939502</ElementReference>
      <Title>
        <TitleText>Stems</TitleText>
      </Title>
      <IsMultiTrack>false</IsMultiTrack>
      <Designation>Master</Designation>
      <Configuration>InterleavedStereoFiles</Configuration>
      <BitDepth>24</BitDepth>
      <SamplingRate>96000</SamplingRate>
      <FileType>BwfFile</FileType>
      <Comment>In Folder - CCarpenter_FancyFloors_96k24b_Bwav_Mixes_031217</Comment>
      <ElementDataCarrierReference>
        <ElementDataCarrierReference>D-3939473</ElementDataCarrierReference>
        <DataCarrierFormat>HierarchicalFileSystemPlus</DataCarrierFormat>
      </ElementDataCarrierReference>
      <ElementSoundRecordingReference>A-3939415</ElementSoundRecordingReference>
    </Element>
    <Element>
      <ElementReference>M-3939481</ElementReference>
      <Title>
        <TitleText>BGV Up</TitleText>
      </Title>
      <IsMultiTrack>false</IsMultiTrack>
      <Designation>Master</Designation>
      <Configuration>InterleavedStereoFiles</Configuration>
      <BitDepth>24</BitDepth>
      <SamplingRate>48000</SamplingRate>
      <FileType>BwfFile</FileType>
      <Comment>CC_IShouldveDugDeeper_R3_BGVocalUp.wav</Comment>
      <ElementDataCarrierReference>
        <ElementDataCarrierReference>D-3939473</ElementDataCarrierReference>
        <DataCarrierFormat>HierarchicalFileSystemPlus</DataCarrierFormat>
      </ElementDataCarrierReference>
      <ElementSoundRecordingReference>A-3939419</ElementSoundRecordingReference>
    </Element>
    <Element>
      <ElementReference>M-3939493</ElementReference>
      <Title>
        <TitleText>Mstr 2</TitleText>
      </Title>
      <IsMultiTrack>false</IsMultiTrack>
      <Designation>Master</Designation>
      <Configuration>InterleavedStereoFiles</Configuration>
      <BitDepth>24</BitDepth>
      <SamplingRate>48000</SamplingRate>
      <FileType>BwfFile</FileType>
      <Comment>CC_IShouldveDugDeeper_R3_Master.wav</Comment>
      <ElementDataCarrierReference>
        <ElementDataCarrierReference>D-3939473</ElementDataCarrierReference>
        <DataCarrierFormat>HierarchicalFileSystemPlus</DataCarrierFormat>
      </ElementDataCarrierReference>
      <ElementSoundRecordingReference>A-3939419</ElementSoundRecordingReference>
    </Element>
    <Element>
      <ElementReference>M-3939510</ElementReference>
      <IsMultiTrack>true</IsMultiTrack>
      <Designation>Master</Designation>
      <Configuration>Nuendo</Configuration>
      <BitDepth>24</BitDepth>
      <SamplingRate>96000</SamplingRate>
      <FileType>BwfFile</FileType>
      <Comment>DoOverMixMaster.npr</Comment>
      <ElementDataCarrierReference>
        <ElementDataCarrierReference>D-3939473</ElementDataCarrierReference>
        <DataCarrierFormat>HierarchicalFileSystemPlus</DataCarrierFormat>
      </ElementDataCarrierReference>
      <ElementSoundRecordingReference>A-3939415</ElementSoundRecordingReference>
    </Element>
    <Element>
      <ElementReference>M-3939512</ElementReference>
      <IsMultiTrack>true</IsMultiTrack>
      <Designation>Master</Designation>
      <Configuration>Nuendo</Configuration>
      <BitDepth>24</BitDepth>
      <SamplingRate>96000</SamplingRate>
      <FileType>BwfFile</FileType>
      <Comment>Redo Mix Master.npr</Comment>
      <ElementDataCarrierReference>
        <ElementDataCarrierReference>D-3939473</ElementDataCarrierReference>
        <DataCarrierFormat>HierarchicalFileSystemPlus</DataCarrierFormat>
      </ElementDataCarrierReference>
      <ElementSoundRecordingReference>A-3939421</ElementSoundRecordingReference>
    </Element>
    <Element>
      <ElementReference>M-3939482</ElementReference>
      <Title>
        <TitleText>BGV Up</TitleText>
      </Title>
      <IsMultiTrack>false</IsMultiTrack>
      <Designation>Master</Designation>
      <Configuration>InterleavedStereoFiles</Configuration>
      <BitDepth>24</BitDepth>
      <SamplingRate>48000</SamplingRate>
      <FileType>BwfFile</FileType>
      <Comment>CC_RhyOfTheSouth_48k24b_Bwav_R3AltBgVocUp.wav</Comment>
      <ElementDataCarrierReference>
        <ElementDataCarrierReference>D-3939473</ElementDataCarrierReference>
        <DataCarrierFormat>HierarchicalFileSystemPlus</DataCarrierFormat>
      </ElementDataCarrierReference>
      <ElementSoundRecordingReference>A-3939417</ElementSoundRecordingReference>
    </Element>
    <Element>
      <ElementReference>M-3939508</ElementReference>
      <Title>
        <TitleText>TV</TitleText>
      </Title>
      <IsMultiTrack>false</IsMultiTrack>
      <Designation>Master</Designation>
      <Configuration>InterleavedStereoFiles</Configuration>
      <BitDepth>24</BitDepth>
      <SamplingRate>48000</SamplingRate>
      <FileType>BwfFile</FileType>
      <Comment>CC_RhyOfTheSouth_48k24b_Bwav_R3AltNoLdVoc.wav</Comment>
      <ElementDataCarrierReference>
        <ElementDataCarrierReference>D-3939473</ElementDataCarrierReference>
        <DataCarrierFormat>HierarchicalFileSystemPlus</DataCarrierFormat>
      </ElementDataCarrierReference>
      <ElementSoundRecordingReference>A-3939417</ElementSoundRecordingReference>
    </Element>
    <Element>
      <ElementReference>M-3956367</ElementReference>
      <Title>
        <TitleText>Aca LV</TitleText>
      </Title>
      <IsMultiTrack>false</IsMultiTrack>
      <Designation>Master</Designation>
      <Configuration>InterleavedStereoFiles</Configuration>
      <BitDepth>24</BitDepth>
      <SamplingRate>96000</SamplingRate>
      <FileType>BwfFile</FileType>
      <Comment>CCr_TheLieInBelieve_Stem_LdVoc_02.wav</Comment>
      <ElementDataCarrierReference>
        <ElementDataCarrierReference>D-3939473</ElementDataCarrierReference>
        <DataCarrierFormat>HierarchicalFileSystemPlus</DataCarrierFormat>
      </ElementDataCarrierReference>
      <ElementSoundRecordingReference>A-3939421</ElementSoundRecordingReference>
    </Element>
    <Element>
      <ElementReference>M-3939509</ElementReference>
      <Title>
        <TitleText>1</TitleText>
      </Title>
      <IsMultiTrack>true</IsMultiTrack>
      <Designation>Master</Designation>
      <Configuration>Nuendo</Configuration>
      <BitDepth>24</BitDepth>
      <SamplingRate>48000</SamplingRate>
      <FileType>BwfFile</FileType>
      <Comment>BS R2 Master.npr - Multitrack Session</Comment>
      <ElementDataCarrierReference>
        <ElementDataCarrierReference>D-3939473</ElementDataCarrierReference>
        <DataCarrierFormat>HierarchicalFileSystemPlus</DataCarrierFormat>
      </ElementDataCarrierReference>
      <ElementSoundRecordingReference>A-3939419</ElementSoundRecordingReference>
    </Element>
    <Element>
      <ElementReference>M-3939501</ElementReference>
      <Title>
        <TitleText>Mstr</TitleText>
      </Title>
      <IsMultiTrack>false</IsMultiTrack>
      <Designation>Master</Designation>
      <Configuration>InterleavedStereoFiles</Configuration>
      <BitDepth>24</BitDepth>
      <SamplingRate>48000</SamplingRate>
      <FileType>BwfFile</FileType>
      <Comment>CC_RhyOfTheSouth_48k24b_Bwav_R3AltMaster.wav</Comment>
      <ElementDataCarrierReference>
        <ElementDataCarrierReference>D-3939473</ElementDataCarrierReference>
        <DataCarrierFormat>HierarchicalFileSystemPlus</DataCarrierFormat>
      </ElementDataCarrierReference>
      <ElementSoundRecordingReference>A-3939417</ElementSoundRecordingReference>
    </Element>
    <Element>
      <ElementReference>M-3939511</ElementReference>
      <Title>
        <TitleText>1</TitleText>
      </Title>
      <IsMultiTrack>true</IsMultiTrack>
      <Designation>Master</Designation>
      <Configuration>Nuendo</Configuration>
      <BitDepth>24</BitDepth>
      <SamplingRate>48000</SamplingRate>
      <FileType>BwfFile</FileType>
      <Comment>BS Remix R2master.npr - Multitrack Session</Comment>
      <ElementDataCarrierReference>
        <ElementDataCarrierReference>D-3939473</ElementDataCarrierReference>
        <DataCarrierFormat>HierarchicalFileSystemPlus</DataCarrierFormat>
      </ElementDataCarrierReference>
      <ElementSoundRecordingReference>A-3939417</ElementSoundRecordingReference>
    </Element>
    <Element>
      <ElementReference>M-3956366</ElementReference>
      <Title>
        <TitleText>Aca LV</TitleText>
      </Title>
      <IsMultiTrack>false</IsMultiTrack>
      <Designation>Master</Designation>
      <Configuration>InterleavedStereoFiles</Configuration>
      <BitDepth>24</BitDepth>
      <SamplingRate>48000</SamplingRate>
      <FileType>BwfFile</FileType>
      <Comment>CC_IShouldveDugDeeper_R2_Stem_LdVoc.wav</Comment>
      <ElementDataCarrierReference>
        <ElementDataCarrierReference>D-3939473</ElementDataCarrierReference>
        <DataCarrierFormat>HierarchicalFileSystemPlus</DataCarrierFormat>
      </ElementDataCarrierReference>
      <ElementSoundRecordingReference>A-3939419</ElementSoundRecordingReference>
    </Element>
    <Element>
      <ElementReference>M-3939487</ElementReference>
      <Title>
        <TitleText>Inst</TitleText>
      </Title>
      <IsMultiTrack>false</IsMultiTrack>
      <Designation>Master</Designation>
      <Configuration>InterleavedStereoFiles</Configuration>
      <BitDepth>24</BitDepth>
      <SamplingRate>96000</SamplingRate>
      <FileType>BwfFile</FileType>
      <Comment>CC_TheLieInBelieve_Inst_02.wav</Comment>
      <ElementDataCarrierReference>
        <ElementDataCarrierReference>D-3939473</ElementDataCarrierReference>
        <DataCarrierFormat>HierarchicalFileSystemPlus</DataCarrierFormat>
      </ElementDataCarrierReference>
      <ElementSoundRecordingReference>A-3939421</ElementSoundRecordingReference>
    </Element>
    <Element>
      <ElementReference>M-3939497</ElementReference>
      <Title>
        <TitleText>Mstr No BGV</TitleText>
      </Title>
      <IsMultiTrack>false</IsMultiTrack>
      <Designation>Master</Designation>
      <Configuration>InterleavedStereoFiles</Configuration>
      <BitDepth>24</BitDepth>
      <SamplingRate>48000</SamplingRate>
      <FileType>BwfFile</FileType>
      <Comment>CC_IShouldveDugDeeper_R3_NoBGVoc.wav</Comment>
      <ElementDataCarrierReference>
        <ElementDataCarrierReference>D-3939473</ElementDataCarrierReference>
        <DataCarrierFormat>HierarchicalFileSystemPlus</DataCarrierFormat>
      </ElementDataCarrierReference>
      <ElementSoundRecordingReference>A-3939419</ElementSoundRecordingReference>
    </Element>
    <Element>
      <ElementReference>M-3939480</ElementReference>
      <Title>
        <TitleText>BGV Up</TitleText>
      </Title>
      <IsMultiTrack>false</IsMultiTrack>
      <Designation>Master</Designation>
      <Configuration>InterleavedStereoFiles</Configuration>
      <BitDepth>24</BitDepth>
      <SamplingRate>96000</SamplingRate>
      <FileType>BwfFile</FileType>
      <Comment>CC_FancyFloors_BgVocUp_01.wav</Comment>
      <ElementDataCarrierReference>
        <ElementDataCarrierReference>D-3939473</ElementDataCarrierReference>
        <DataCarrierFormat>HierarchicalFileSystemPlus</DataCarrierFormat>
      </ElementDataCarrierReference>
      <ElementSoundRecordingReference>A-3939415</ElementSoundRecordingReference>
    </Element>
    <Element>
      <ElementReference>M-3939491</ElementReference>
      <Title>
        <TitleText>LVU</TitleText>
      </Title>
      <IsMultiTrack>false</IsMultiTrack>
      <Designation>Master</Designation>
      <Configuration>InterleavedStereoFiles</Configuration>
      <BitDepth>24</BitDepth>
      <SamplingRate>48000</SamplingRate>
      <FileType>BwfFile</FileType>
      <Comment>CC_RhyOfTheSouth_48k24b_Bwav_R3AltLdVocUp.wav</Comment>
      <ElementDataCarrierReference>
        <ElementDataCarrierReference>D-3939473</ElementDataCarrierReference>
        <DataCarrierFormat>HierarchicalFileSystemPlus</DataCarrierFormat>
      </ElementDataCarrierReference>
      <ElementSoundRecordingReference>A-3939417</ElementSoundRecordingReference>
    </Element>
    <Element>
      <ElementReference>M-3939506</ElementReference>
      <Title>
        <TitleText>TV</TitleText>
      </Title>
      <IsMultiTrack>false</IsMultiTrack>
      <Designation>Master</Designation>
      <Configuration>InterleavedStereoFiles</Configuration>
      <BitDepth>24</BitDepth>
      <SamplingRate>96000</SamplingRate>
      <FileType>BwfFile</FileType>
      <Comment>CCarpenter_TheLieInBelieve_NoLdV_01.wav</Comment>
      <ElementDataCarrierReference>
        <ElementDataCarrierReference>D-3939473</ElementDataCarrierReference>
        <DataCarrierFormat>HierarchicalFileSystemPlus</DataCarrierFormat>
      </ElementDataCarrierReference>
      <ElementSoundRecordingReference>A-3939421</ElementSoundRecordingReference>
    </Element>
    <Element>
      <ElementReference>M-3939488</ElementReference>
      <Title>
        <TitleText>LVU</TitleText>
      </Title>
      <IsMultiTrack>false</IsMultiTrack>
      <Designation>Master</Designation>
      <Configuration>InterleavedStereoFiles</Configuration>
      <BitDepth>24</BitDepth>
      <SamplingRate>96000</SamplingRate>
      <FileType>BwfFile</FileType>
      <Comment>CC_FancyFloors_LdVocUp_01.wav</Comment>
      <ElementDataCarrierReference>
        <ElementDataCarrierReference>D-3939473</ElementDataCarrierReference>
        <DataCarrierFormat>HierarchicalFileSystemPlus</DataCarrierFormat>
      </ElementDataCarrierReference>
      <ElementSoundRecordingReference>A-3939415</ElementSoundRecordingReference>
    </Element>
    <Element>
      <ElementReference>M-4002283</ElementReference>
      <Title>
        <TitleText>Aca LV</TitleText>
      </Title>
      <IsMultiTrack>false</IsMultiTrack>
      <Designation>Master</Designation>
      <Configuration>InterleavedStereoFiles</Configuration>
      <BitDepth>24</BitDepth>
      <SamplingRate>48000</SamplingRate>
      <FileType>BwfFile</FileType>
      <Comment>CC_RhyOfTheSouth_48k24b_Bwav_R3LDVocStem.wav</Comment>
      <ElementDataCarrierReference>
        <ElementDataCarrierReference>D-3939473</ElementDataCarrierReference>
        <DataCarrierFormat>HierarchicalFileSystemPlus</DataCarrierFormat>
      </ElementDataCarrierReference>
      <ElementSoundRecordingReference>A-3939417</ElementSoundRecordingReference>
    </Element>
  </ElementList>
</rin:RecordingInformationNotification>
'));
    }

    /**
     * Handle a request to generate a RIN export
     *
     * @param  Request $request
     * @return Response
     */
    public function export(Request $request)
    {

    }
}
