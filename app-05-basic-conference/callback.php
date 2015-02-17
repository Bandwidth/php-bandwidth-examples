<?php
require_once("../config.php");
require_once("./config.php");

// Callback for Basic Conference
// this should attempt to call each
// recipient afterwards notify all recipients of
// a join or exit

// Important: When adding calls this
// conference will retrieve your last confenrence
// as a reuslt do NOT run this example and another conference simulataneuly

$client = new Catapult\Client;

// This listens for a conference 
// member joining
//$event = new Catapult\Event;
$event = new \stdClass;
$event->from = "+22332";
$event->id = "c-id";

$event->eventType = "incoming";
// our call is incoming
// this means it is time to answer
// and start our conference!
if ($event->eventType == "incoming") {
    $call = new Catapult\Call($event->id);

    if ($event->from == $application->conferenceFromNumber) {
      $call->answer();

      // we can now 
      // start our conference
      $conference = new Catapult\Conference(array(
        "callId" => $call->id
      ));
      // save this conference
      // to our database
      addRecord($application->datatable, array($call->from, $conference->id), array("callFrom", "conferenceId"));

   } else {

     // a user called in.
     // we need to make sure
     // our conference is started
     // and that this is a valid user
  
     $last = getRow(sprintf(
      "SELECT * FROM `%s` WHERE callFrom = '%s'",
      $call->from)); 
     $conferences = new Catapult\Conference($last['conferenceId']);
      
     if (in_array($event->from, $application->conferenceAttendees)) {

       /**
        * accept this
        * user and add him to
        * our conference
        */

        $call->accept();
        $conference->addMember(array(
          "joinTone" => false,
          "leaveTone" => false,
          "callId" => $call->id
        )); 

     } else {
       // user is not on the attendance list
       // we should accept
       // and warn

       $call->accept();
       $call->speakSentence(array(
          "sentence"=>$application->conferenceNoEntry,
          "voice"=>$application->conferenceVoice
       ));
        
     }
   }
} else if ($eventType == "conference-member") {

  // use the conference voice
  // for speaking 
  $voice = new Catapult\Voice($application->conferenceVoice);
  $conference = new Catapult\Conference($event->id);

  // how many conference members
  // are there
  $activeMembers = $conference->activeMembers;

  // is this for a conference
  // member joining or leaving
  if ($event->state == "active") {
    $conference->speakSentence(array(
      "voice" => $voice,
      "sentence" => "A user has joined. There is now $activeMembers in the conference"
    ));
  } else if ($event->state == "complete") {

    $conference->speakSentence(array(
      "voice" => $voice,
      "sentence" => "A user has left. There is now $activeMembers in the conference"
    ));

    $db->query(sprintf(
      "DELETE FROM `%s` WHERE callFrom = '%s'", $application->datatable, $call->from
    ));


  }
} 
