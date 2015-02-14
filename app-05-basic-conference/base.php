<?php

require_once(realpath("../config.php"));
require_once(realpath("./config.php"));

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
$query = $db->query("SELECT * FROM `Basic Conference`; ");
$conferencesCnt = count($query) - 1;


// make sure our from number
// is a valid one
$phoneNumber = new Catapult\PhoneNumber($application->conferenceFromNumber);

if (!($phoneNumber->isValid())) {
  $message = "Your phone number is not in E.164 format";
}

// is this a phone in our
// catapult list
$phoneNumbers = new Catapult\PhoneNumbersCollection;
$phoneNumbers->listAll();
$phoneNumbers->find(array("number" => $application->conferenceFromNumber));


if ($phoneNumbers->isEmpty()) {
  $message = $application->conferenceFromNumber . " is not a phone number in your catapult list";
}

// make sure all outgoing
// numbers are in proper
// format
foreach ($application->conferenceMembersToAdd as $cMember) {
  $phoneNumber = new Catapult\PhoneNumber($cMember);
  if (!($phoneNumber->isValid())) {
    $message .= "conference number: $cMember is not a valid phone number. Please use E.164";
  }
}

if (!isset($message)) {
  $status = "success";
  $message = "Nice you can start this conference by clicking initiate or phoning yourself";
}


?>
