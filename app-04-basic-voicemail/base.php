<?php
require_once(__DIR__."/../config.php");
require_once(__DIR__."/config.php");

// Base of Basic VoiceMail
// this will perform the flow
// as described here:
// 
// https://catapult.inetwork.com/docs/guides/voicemail/

// IMPORTANT: 
// This file is the base wrapper behind
// the interface. Implementors should look in
// callback.php which is the application's 
// implementation. This provides rules for
// checking whether your application has been
// setup properly.


$client = new Catapult\Client;

// get all the voicemail so far
//
$voicemail = $db->query("SELECT * FROM `" . $application->applicationTable. "`; ");
$voicemailCnt = getCount(sprintf("SELECT COUNT(*) as count FROM `%s`; ", $application->applicationTable));

$status = 'error';

// Validation 1
// 
// Check if our voicemail number
// is valid
$PhoneNumber = new Catapult\PhoneNumber($application->voicemailNumber);

if (!$PhoneNumber->isValid()) {
  $message = "Voice Mail number is invalid, this needs to be in E.164 format";
}


// Validation 2 
//
// check if our voicemail number is
// listed in our Catapult account
$PhoneNumbers = new Catapult\PhoneNumbers;
$PhoneNumbers = $PhoneNumbers->listAll(array("size" => 1000))
                             ->find(array("number" => $application->voicemailNumber));

if ($PhoneNumbers->isEmpty()) {
  $message = "Voice Mail number is not listed under your Catapult account";
}

// Validation 3
// 
// Make sure the listed voice
// is valid
$Voice = new Catapult\Voice($application->voicemailVoice);
if (!$Voice->isValid()) {
  $message = "$Voice is not a valid voice for Catapult";
}

// Optional Validation
//
// check if the MediaURL is
// valid media MIME type
$Media = new Catapult\MediaURL($application->voicemailFile);

if (!$Media->isValid()) {
  $message = "Voice mail file does not exist.";
}

if (!isset($message)) {
  $message = $application->voicemailNumber . " is listening to you can send voice mail to it.";
  $status = 'success';
}
?>
