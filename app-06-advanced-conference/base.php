<?php

require_once(realpath(__DIR__."/../config.php"));
require_once(realpath(__DIR__."/config.php"));

// IMPORTANT: 
// This file is the base wrapper behind
// the interface. Implementors should look in
// callback.php which is the application's 
// implementation. This provides rules for
// checking whether your application has been
// setup properly.
//

Catapult\Credentials::setPath(__DIR__."/..");
$client = new Catapult\Client;
$conferences = getQuery(sprintf("SELECT * FROM %s;", $application->applicationTable));
$conferencesData = getQuery(sprintf("SELECT * FROM %s", $application->applicationDataTable));
$conferencesCnt = getCount(sprintf("SELECT COUNT(*) as count FROM %s;", $application->applicationTable));


$success = 'error';
// Validation 1.
// check if the initial number is valid
$phoneNumbers = new Catapult\PhoneNumbersCollection;
$phoneNumbers->listAll();
$phoneNumbers->find(array("number" => $application->conferenceFromNumber));

if ($phoneNumbers->isEmpty()) {
  $message = $application->conferenceFromNumber . " is not a phone number in your catapult list"; 
}


$phoneNumber = new Catapult\PhoneNumber($application->conferenceFromNumber);
if (!$phoneNumber->isValid()) {
  $message = $application->conferenceFromNumber . " is not a valid number";
}


// Validation 2 Optional
// we play a beep
// which needs to be
// checked
$media = new Catapult\MediaURL($application->conferenceBeepFile);

if (!$media->isValid()) {
  $message = $application->conferenceBeepFile . " is not a valid media file.";
}


// Recommended Validation 3.
// check if each conference attendee's
// number is valid
foreach ($application->conferenceAttendees as $cMember) {
  $phoneNumber = new Catapult\PhoneNumber($cMember);
  if (!($phoneNumber->isValid())) {
    if (!isset($message)) {
      $message = "";
    }
    $message .= "<br />conference number: $cMember is not a valid phone number. Please use E.164";
  }
}

if (!isset($message)) {
  $status = "success";
  $message = "You can begin this conference, use number: " . $application->conferenceFromNumber; 
}

?>
