<?php

// Callback for Advanced conference 
// here is what we need:
//
// 1. Answer primary incoming call
// 2. Initiate a conference
// 3. For each conference member add a call
// 
// Listening:
// 1. Make sure each conference member enter his/her unique gather key  
// and sets a fullname
// 2. Display a message for the member it should tell him his nickname
// and alert everyone of his joining
// 
require_once("../config.php");
require_once("config.php");

$client = new Catapult\Client;
// Start our event this will 
// be multipurpose and used for Gathers
// Calls and Conferences
$event = new Catapult\Event;

if ($event->eventType == "incoming") {

  $call = new Catapult\Call($event->id);

  if ($call->from == $application->conferenceFromNumber) {

    $call->answer();

    $conference = new Catapult\Conference;
    $conference->create(array(
      "callId" => $call->id
    ));

    addRecord($application->datatable, array($call->from, $conference->id), array("callFrom", "conferenceId"));

  } else {

    /**
     * someone has called conference
     * 
     * create a gather for him and 
     * and answer the call
     */
     $last = getRow(sprintf(
      "SELECT * FROM `%s` WHERE callFrom = '%s'",
      $call->to)); 
  

     $conference = new Catapult\Conference($last['conferenceId']);

    $gather = new Catapult\Gather(array(
      "maxDigits" => $application->conferenceGatherMaxDigits,
      "terminatingDigits" => $application->conferenceGatherTerminatingDigits
    ));

    $call = new Catapult\Call($event->id);

    if (in_array($call->from, $application->conferenceAttendees)) {
      $call->answer();

      // generate our dtmf code
      // for entry
      // let's make it a random 4 digit number 
      // then save it in our database
      //
      // we will need to later
      // check if this code matches
      $code = rand(1000, 9999);
      addRecord(array($code,$call->to, $call->from, $conference->id,""), array("code", "callFrom", "receiverCallFrom", "conferenceId","fullName"));
      
      $gather = new Catapult\Gather(array(
        "callId" => $call->id,
        "maxDigits" => $application->conferenceGatherMaxDigits,
        "terminatingDigits" => $application->conferenceGatherTerminatingDigitsCode
      ));

      $call->speakSentence(array(
        "voice" => $application->conferenceVoice,
        "sentence" => $application->conferenceEnterDigits
      ));


    }


  }

} elseif ($event->eventType == "dtmf") {
  /* Listen process 1.
   *
   * lookup the unique digits
   * for this user when they match
   * allow entrance. When they don't 
   * display application->noEntryMessage
   *
   *
   * When allowed in create our conferenceMember
   * and alert the room of their presence
   */

  $gather = new Catapult\Gather($event->gatherId);
  $call = new Catapult\Call($event->id);

  if (is_numeric($gather->digits)) {
   $last = getRow(sprintf(
      "SELECT * FROM `%s` WHERE callFrom = '%s' AND receiverCallFrom = '%s'",
      $application->datatable, $call->to, $call->from)); 

    $code = $last['code']; 

    // optional add a main digit code
    // this is for testing purposes
    // and should be omitted for real use
    // case

    if ($gather->digits == $code
       || $gather->digits == $application->mainGatherDigits) {


       // the user has entered
      // the right digits
      // we can  let him in at this point
      $conference = new Catapult\Conference($last['conferenceId']);
    
      $call = new Catapult\Call($event->callId);

      $call->speakSentence(array(
        "voice" => $application->conferenceVoice,
        "sentence" => $application->conferenceJoinMessage
      ));

      $call->speakSentence(array(
        "voice" => $application->conferenceVoice,
        "sentence" => $application->conerenceFullname
      ));

      $conference->addMember(array(
        "callId" => $callId
      ));


    } else {


      // wrong input for
      // the conference
      // when this happens
      // we need to alert
      // the user
      //
      
      $call->speakSentence(array(
        "voice" => $application->conferenceVoice,
        "sentence" => $application->conferenceNoEntry
      ));
             
    }

  } 

} else if ($event->eventType == "conference-member") {

  /**
   * Listen process 2.
   * this is when our user has joined
   * the conference we can alert the others
   * their full name.
   */
  
   $conferenceMember = new Catapult\ConferenceMember;
   $call = new Catapult\Call($conferenceMember->callId);
   $conference = new Catapult\Conference($conferenceMember->conferenceId);

   $name = getRow(sprintf(
      "SELECT name FROM `Advanced Conferences Data` WHERE callFrom = '%s' AND conferenceId = '%s';", $call->from, $conference->id
   ));


   if ($event->state == "active") {
     $conference->speakSentence(array(
        "sentence" => "A user has entered the room.",
        "voice" => $application->conferenceVoice
     ));
   } else if ($event->state == "complete") {

      // a user has left. We should alert everyone
      // and cleanup
      $conference->speakSentence(array(
        "sentence" => "A user has left the room.",
        "voice" => $application->conferenceVoice
      ));
    
      $db->query(sprintf(
        "DELETE FROM `%s` WHERE receiverCallFrom = '%s'", $application->datatable, $call->from
      ));
   }
} else if ($event->eventType == "hangup") {

  // this means the confernece
  // is over. we should clean all our data and leave
 
  $db->query(sprintf(
    "DELETE FROM `%s` WHERE conferenceId = '%s'; ", $application->datatable, $conference->id
  ));


}
?>
