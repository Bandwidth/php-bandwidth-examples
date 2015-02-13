<?php
require_once("../php-bandwidth/source/Catapult.php");
require_once("../config.php");
require_once("./config.php");

/**
 * Base of Basic VoiceMail
 * this will perform the flow
 * as described here:
 * 
 * https://catapult.inetwork.com/docs/guides/voicemail/
 */

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
$voicemail = $db->query("SELECT * FROM `" . $application->applicationName . "`; ");
$voicemailCnt = count($voicemail) - 1; // SQLite returns + 1


$status = 'error';

$PhoneNumber = new Catapult\PhoneNumber($application->voicemailNumber);

if (!$PhoneNumber->isValid()) {
  $message = "Voice Mail number is invalid, this needs to be in E.164 format";
}


// list all the numbers
// and make sure the provided
// one is in our collection

$PhoneNumbers = new Catapult\PhoneNumbers;
$PhoneNumbers = $PhoneNumbers->listAll()
                             ->find(array("number" => $application->voicemailNumber));

if ($PhoneNumbers->isEmpty()) {
  $message = "Voice Mail number is not listed under your Catapult account";
}

// make sure the voice 
// is a valid one
$Voice = new Catapult\Voice($application->voicemailVoice);
if (!$Voice->isValid()) {
  $message = "$Voice is not a valid voice for Catapult";
}

// do a check on the file
// voicemailFile
// remember this needs
// to be a URI 
$Media = new Catapult\MediaURL($application->voicemailFile);


if (!$Media->isValid()) {
  $message = "Voice mail file does not exist.";
}

if (!isset($message)) {
  $message = $application->voicemailNumber . " is listening to you can send voice mail to it.";
  $status = 'success';
}
?>
