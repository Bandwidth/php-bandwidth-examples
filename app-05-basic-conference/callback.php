<?php
require_once("./config.php");
require_once("../config.php");

// Callback for Basic Conference
// this should attempt to call each
// recipient afterwards notify all recipients of
// a join or exit

// Important: When adding calls this
// conference will retrieve your last confenrence
// as a reuslt do NOT run this example and another conference simulataneuly

// This listens for a conference 
// member joining
$event = new Catapult\Event;

// our call is incoming
// this means it is time to answer
// and start our conference!
if ($event->type == "incoming") {

    $call = new Catapult\Call($event->id);
    $call->answer();

    // we can now 
    // start our conference
    $conference = new Catapult\Conference(array(
      "callId" => $call->id
    ));


    // start a call for each
    // recipient
    foreach ($application->conferenceMembersToAdd as $cmn) {
      $call1 = new Catapult\Call(array(
        "from" => $application->conferenceNumber,
        "to" => $cmn
      ));
    } 


} else if ($event->type == "answer") {
  // a conference member has accepted the call
  // add him to our conference

    $conferences = new Catapult\ConferenceCollection;
    $conferences->listAll();
    $conference = $conferences->last();
       
  // also lookup the call for this conference
  // this needs to be one of our conference members!
    $call = new Catapult\Call($event->id);
    if (in_array($call->to,$application->conferenceMembersToAdd)) {
      $conference->addMember(array(
        "joinTone" => false,
        "leaveTone" => false,
        "callId" => $call->id
      ));
    }

} else if ($event->type == "conference-member") {

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
  }
} 
