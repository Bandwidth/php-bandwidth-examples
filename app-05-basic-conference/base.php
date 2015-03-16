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

$client = new Catapult\Client;

// First check what's in our database
// this keeps all our conferences together
$conferences = $db->query(sprintf("SELECT * FROM `%s`; ", $application->applicationTable));
$conferencesCnt = getCount(sprintf("SELECT COUNT(*) as count FROM `%s`;", $application->applicationTable));

// Validation 1
//
// make sure our from number
// is a valid one

$phoneNumber = new Catapult\PhoneNumber($application->conferenceFromNumber);

if (!($phoneNumber->isValid())) {
  $message = "Your phone number is not in E.164 format";
}

// Validation 2
//
// is this a phone in our
// catapult list
$phoneNumbers = new Catapult\PhoneNumbersCollection;
$phoneNumbers->listAll(array("size" => 1000));
$phoneNumbers->find(array("number" => $application->conferenceFromNumber));


if ($phoneNumbers->isEmpty()) {
  $message = $application->conferenceFromNumber . " is not a phone number in your catapult list";
}

// Recommended Validation 3
//
// make sure all outgoing
// numbers are in proper
// format. 
foreach ($application->conferenceAttendees as $cMember) {
  $phoneNumber = new Catapult\PhoneNumber($cMember);
  if (!($phoneNumber->isValid())) {
    $message .= "conference number: $cMember is not a valid phone number. Please use E.164";
  }
}

if (!isset($message)) {
  $status = "success";
  $message = sprintf("You can begin this conference use number: %s to begin the conference", $application->conferenceFromNumber);
}


?>
