<?php
require_once(__DIR__."/../config.php");
require_once(__DIR__ . "/config.php");
//
// Voice Reminders with Catapult. This
// will perform the flow as described here:
// https://catapult.inetwork.com/docs/guides/voicemail/
//
// Before anything you should set up your application.json
// Needs:
// voiceReminderText -- this will be used in the call
// voiceReminderNumber -- this will be used as a number
// voiceReminderVoice -- this will be the voice used to render speech


// IMPORTANT: 
// This file is the base wrapper behind
// the interface. Implementors should look in
// callback.php which is the application's 
// implementation. This provides rules for
// checking whether your application has been
// setup properly.



Catapult\Credentials::setPath(__DIR__."/..");
$client = new Catapult\Client;
$reminders = getQuery(sprintf("SELECT * FROM %s",  $application->applicationTable));
$remindersCnt = getCount(sprintf("SELECT COUNT(*) as count FROM %s",$application->applicationTable));

// Implementors Note:
// when using apps: 01 and 02 you may have noticed we
// used the catapult listing feature for this
// given we cannot keep track of all reminders with this
// feature we're using SQLite to save each reminder

$recording = new Catapult\Recording;


// Validation 1
//
// make sure the number is
// in E.1464 format
$PhoneNumber = new Catapult\PhoneNumber($application->voiceReminderNumber);
if (!$PhoneNumber->isValid()) {
  $message = 'Voice Reminder number is not in E.164 format';
}

// Validation 2
//  
// check if its listed
// in our Catapult account
$PhoneNumbers = new Catapult\PhoneNumbersCollection;
$PhoneNumbers->listAll(array("size" => 1000));
$PhoneNumbers->find(array("number" => $application->voiceReminderNumber));

if ($PhoneNumbers->isEmpty()) {
  $message = "This number is not listed under your Catapult account";
}

// Validation 3
//
// check if the voice 
// is a valid Catapult voice
$Voice = new Catapult\Voice($application->voiceReminderVoice);

if (!$Voice->isValid()) {
  $message = "$Voice is not a valid voice for Catapult";
}

if (!isset($message)) {
  $message = $application->voiceReminderNumber . " is listening for voice reminders, go a head and dial";
  $status = "success";
}
?>
