<?php
require_once(__DIR__."/../config.php");
require_once(__DIR__."/config.php");


// Basic Conferences 
//
// This applications provides basic implementation of conferences
// you will need to initiate this conference by dialing
// in from the number provided in application.json. Also
// the numbers listed in conferenceAttendees will be used
// to validate whos allowed in.


// Implementors Note:
// When adding calls this conference will retrieve your last confenrence
// as a result do NOT run this example and another conference simulataneuly


// Set up the client
$client = new Catapult\Client;


// Important if you want to accept incoming
// calls automatically change this to AnswerCallEvent
// this will only work on applications that have
// auto 'incoming' to off
$inboundCallEvent = new Catapult\IncomingCallEvent;
// comment out if using 
// auto incoming
// $inboundCallEvent = new Catapult\AnswerCallEvent;
$conferenceMemberEvent = new Catapult\ConferenceMemberEvent;
$errorCallEvent = new Catapult\ErrorCallEvent;
$timeoutCallEvent = new Catapult\TimeoutCallEvent;
$hangupCallEvent = new Catapult\HangupCallEvent;

// our call is incoming
// this means it is time to answer
// and start our conference!
if ($inboundCallEvent->isActive()) {

    // Step 1
    //
    // Take incoming calls and 
    // validate them agaisnt our conferenceFromNumber
    //
    $call = new Catapult\Call($inboundCallEvent->callId);

    // Using one number to dial 
    // in is best in these cases
    // as it would only be
    // started by one member
    if ($call->to == $application->conferenceFromNumber ) {

      // Important
      // 
      // when using the manual
      // approach this is needed
      if ($call->state == Catapult\CALL_STATES::started) {
        $call->accept();
      }

      $call->speakSentence(array(
        "sentence" => $application->conferenceInitiate,
        "voice" => $application->conferenceVoice,
        "gender" => $application->conferenceVoiceGender
      ));

      // we can now 
      // start our conference
      $conference = new Catapult\Conference(array(
        "from" => $call->to
        //"callId" => $call->id
      ));

      // Optional 
      //
      // save this conference's basic data
      // to our database
      $date = new DateTime;
      addRecordBasic($application->applicationTable, array($call->from, $call->to, $conference->id, $date->format("Y-m-d")));

      // Important
      //
      // we need to save the conference's
      // data in our database
      addRecord($application->applicationTable, array($call->from, $conference->id), array("call_from", "conference_id"));

   } else {

     // a user called in.
     // we need to make sure
     // our conference is started
     // and that this is a valid user


      // Important
      //
      // when using the manual approach
      // we need to accept the call
     if ($call->state == Catapult\CALL_STATES::started) {
      $call->accept();
     }
  
     $last = getRow(sprintf(
      "SELECT * FROM %s WHERE call_from = '%s'",
      $application->applicationDatable, $call->from)); 
     $conferences = new Catapult\Conference($last['conference_id']);
     

      // Optional 
      //
      // this conference checks whether
      // the caller is from our accepted
      // list. This is however optional
     if (in_array($event->from, $application->conferenceAttendees)) {

        // accept this
        // user and add him to
        // our conference
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
          "gender" => $application->conferenceVoiceGender,
          "voice"=>$application->conferenceVoice
       ));
     }

     // Optional
     //
     // we can add the members
     // entry in our database 
     // 
     addRecord($application->applicationDatatable, array($conference->id, $call->id, $call->from), array("conference_id", "call_id", "call_from"));
   }
} 

if ($conferenceMemberEvent->isActive()) {

  // Step 2.
  // once the conference member
  // has been accepted check 
  // which state the member is in
  // this can be in either active or complete.
  // Here we display a message according
  // to it.

  // use the conference voice
  // for speaking 
  $call = new Catapult\Call($conferenceMemberEvent->callId);
  $voice = new Catapult\Voice($application->conferenceVoice);
  $conference = new Catapult\Conference($conferenceMemberEvent->conferenceId);
  $conferenceMember  = new Catapult\ConferenceMember($conference->id, $conferenceMemberEvent->memberId);

  // Recommended
  //
  // we should stop all voice
  // for this call.
  $call->stopSentence();


  // how many conference members
  // are there
  $activeMembers = $conference->activeMembers;

  // is this for a conference
  // member joining or leaving
  if ($conferenceMemberEvent->state == Catapult\CONFERENCE_MEMBER_STATES::STARTED) {
    $conference->speakSentence(array(
      "voice" => $voice,
      "gender" => $application->conferenceVoiceGender,
      "sentence" => "A user has joined. There is now $activeMembers in the conference"
    ));
  } else if ($conferenceMemberEvent->state == Catapult\CONFERENCE_MEMBER_STATES::DONE) {

    $conference->speakSentence(array(
      "voice" => $voice,
      "gender" => $application->conferenceVoiceGender,
      "sentence" => "A user has left. There is now $activeMembers in the conference"
    ));
  }
} 

if ($errorCallEvent->isActive()) {
  // Recommended
  //
  // treat all error call events  
  // Note: you can use Catapult\CALL_ERROR
  // in doing this.
  // i.e: 
  // if ($errorCallEvent->error == Catapult\CALL_ERROR::NORMAL_CLEARING) {
  // // .. treat normal clearing error here
  //
  //}
}

if ($timeoutCallEvent->isActive()) {
  // Recommended
  //
  // treat all timeoutCallEvents
  $call = new Catapult\Call($timeoutCallEvent->callId);
  $call->hangup();
}

if ($hangupCallEvent->isActive()) {
  // Recommended
  //
  // treat a hangup we should probably cleanup
  // any user data. since this is a basic
  // conference and we're not storing any
  // we aren't doing anything here.
}
  
