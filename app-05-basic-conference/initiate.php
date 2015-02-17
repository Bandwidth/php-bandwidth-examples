<?php
require_once("../config.php");
require_once("config.php");

// Application 005 initiation. This will
// look at the applicationNumbers and initiate
// the call foreach member. It should be accessible
// from the web interface

// !important make sure the callback url for calls
// is properly setup as this will impact where
// conferences are responded to.

$client = new Catapult\Client;
// First we start our primary
// call. This should be using ourselves
$call = new Catapult\Call(array(
  "from" => $application->conferenceFromNumber,
  "to" => $application->conferenceFromNumber
));

// add this as saved record
// the conference 
$date = new DateTime;
addRecord(array($application->conferenceFromNumber, $application->conferenceMembersToAdd, $application->conferenceMembersToAdd, $date->format("Y-M-D H:i:s")));

?>