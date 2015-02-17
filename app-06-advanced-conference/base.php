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
$query = $db->query("SELECT * FROM `Advanced Conference`;");
$conferencesCnt = count($query)-1;

$phoneNumbers = new Catapult\PhoneNumbersCollection;
$phoneNumbers->listAll();
$phoneNumbers->find(array("number" => $application->conferenceFromNumber));

if ($phoneNumbers->isEmpty()) {
  $message = $application->conferenceFromNumber . " is not a phone number in your catapult list"; 
}

foreach ($application->conferenceAttendees as $cMember) {
  $phoneNumber = new Catapult\PhoneNumber($cMember);
  if (!($phoneNumber->isValid())) {
    $message .= "conference number: $cMember is not a valid phone number. Please use E.164";
  }
}

if (!isset($member)) {
  $status = "success";
  $message = "Nice you start this conference"; 
}

?>
