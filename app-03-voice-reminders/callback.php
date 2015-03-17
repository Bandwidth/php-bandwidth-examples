<?php
// include both configs

require_once(__DIR__."/../config.php");
require_once(__DIR__."/config.php");

// Voice Reminders Application 
//
// this defines a simple voice reminder
// application which functions like:
//
// http://catapult.inetwork.com/guides/voice-reminders


$client = new Catapult\Client;

// By default we are accepting auto incoming
// calls if you plan on using a manual
// approach comment this out
$inboundCallEvent = new Catapult\AnswerCallEvent;
// uncomment if using
// a manual approach
//$inboundCallEvent = new Catapult\IncomingCallEvent;
$speakCallEvent = new Catapult\SpeakCallEvent;
$errorCallEvent = new Catapult\ErrorCallEvent;
$timeoutCallEvent = new Catapult\TimeoutCallEvent;
$hangupCallEvent = new Catapult\HangupCallEvent;
$recordingCallEvent = new Catapult\RecordingCallEvent;
$gatherCallEvent = new Catapult\GatherCallEvent;

if ($inboundCallEvent->isActive()) {
   // Step 1
   //
   // Accept an incoming call

   $call = new Catapult\Call($inboundCallEvent->callId);
   // Important 
   //
   // when using 
   // a manual approach
   // this is needed
   //
   if ($call->state == Catapult\CALL_STATES::started) {
    $call->accept(); 
   }

   $voice = new Catapult\Voice($application->voiceReminderVoice);

   // Create a gather.
   // This will be used for the user's
   // dialed numbers. We will get to this
   // in step two

   $gather = new Catapult\Gather($inboundCallEvent->callId, array(
      "terminatingDigit" => $application->voiceReminderTerminatingDigits,
      // we need to allow an inter digit
      // timeout that is big enough for
      // our speech and bargeable considerings
      "interDigitTimeout" => 10,
      // Initially no digits
      // are needed for the Gather
      // this Gather will only be activated
      // on a terminatingDigit
      // maxDigits => 10,

      // Recommended
      //
      // Gathers allow us to 
      // specify a prompt speech
      // on creation 
      "prompt" => array(
        "voice" => $application->voiceReminderSentence,
        "sentence" => $application->voiceReminderSentence,
        "gender" => $application->voiceReminderVoiceGender,
        // when bargeable is set
        // the first dtmf digit will interrupt this speech
      )
   ));
   // Important
   // 
   // since we are using dates and 
   // files we will need SQlite in storing
   // this data
   $date = new DateTime;

  
   addRecordBasic( $application->applicationTable, array($call->from, $call->to,$call->id, $date->format("Y-m-d")));
   addRecord($application->applicationDataTable, array($call->id, 1), array("call_id", "initiated"));

   sleep(12);

   $call->playAudio($application->voiceReminderBeepFile);

   // Important
   //
   // we need to enable recordings
   // as our speech segment requires
   // it.
   $call->update(array(
      "recordingEnabled" => true
   ));

} 
if ($recordingCallEvent->isActive()) {
  // Step 3:
  //
  //
  // this is a callback to our recording
  // being complete. It is called manually
  // when we know the speech segment is
  // over
  $recording = new Catapult\Recording($recordingCallEvent->recordingId);
  $call = new Catapult\Call($recordingCallEvent->callId);

  // Recommended
  //
  // we need to check if 
  // the recording status is 
  // complete
  // 
  // Implementors Note:
  // 
  // we use Catapult constants
  // to check our statuses as it is better
  // for debugging purposeses
  if ($recordingCallEvent->status == Catapult\RECORDING_STATUSES::completed) {
    // Implementors Note:
    // 
    // recording objects provide
    // a way to get there media file
    // we need to use this
    // We can optionally save this media
    // file where we like, for demo purposes
    // we will only use the recordingId
    // fetching it on request
    $media = $recording->getMediaFile();

    // Recommended
    //
    // for best results we should save
    // the recording's id as we can later access
    updateRow(sprintf(
      "UPDATE %s SET recording_id = '%s' WHERE call_id = '%s'", $application->applicationDataTable, $recording->id, $call->id
    ));
  }
  // Recommended
  //
  // handling the recording errors
  // can be of benefit here since we
  // are only using files for display purposes
  // we will log this as an empty recording
  // (set in the SQLite field)
  if ($recording->state == Catapult\RECORDING_STATUSES::error) {
    updateRow(sprintf(
      "UPDATE %s SET message = ' %s', recording_id = '%s' WHERE call_id = '%s'", 
      $application->applicationDataTable, 
      $application->applicationErrorMessage,
      $recording->id,
      $call->id
    ));
  }
}


if ($gatherCallEvent->isActive()) {
  // Step 2: 
  //
  // The user has entered digits we can
  // use our gather in obtaining what was
  // entered
  //
  $call = new Catapult\Call($gatherCallEvent->callId);
  // Recommended
  // 
  // It is important that
  // we only let active calls
  // pass
  if ($call->state == Catapult\CALL_STATES::active) {
    $gather = new Catapult\Gather($call->id, $gatherCallEvent->gatherId);


    // Implementors Note:
    //
    // our gather object provides us with 
    // the digits right before our terminating digit
    // we can assign this to a variable. Further the state
    // will render a 'complete' when our terminating digit is pressed
    // OR when the maxDigits are met

    $digits = $gather->digits;
    $state = $gather->state;
    $reason = $gather->reason;

    $started = false; // this flag will enable us to do two speeches in parallel (needed)
    $finished = false; // this flag will tell us when we're done


    // now we need to get
    // the row that was 
    // last made  for this
    // user. We should always
    // get the last row as this
    // would be our call

    $record = getRow(sprintf(
       "SELECT * FROM %s WHERE call_id = '%s' LIMIT 1", $application->applicationDataTable,$call->id)
    );


    // Recommended
    //
    // since we're using SQLite things can
    // happen we will check if the record exists 
    // if it doesn't warn and exit
    if (!$record) {

      $call->speakSentence(array(
        "sentence" => $application->voiceReminderError,
        "voice" => $application->voiceReminderVoice,
        "gender" => $application->voiceReminderVoiceGender
      ));
      sleep(10);
      $call->hangup();
      exit(1);
    }

    // get where we are in
    // the voice reminder 
    // implementation in helpers.php
    $currentSequence = getCol($record);

    // Recommended
    //
    // Treating the inter digit timeout and max digits will
    // come to use when we need to re-warn the
    // user of his progress we can simply get the current
    // gather and create a new one for the same
    // context

    if ($state == Catapult\GATHER_STATES::completed && ($reason == Catapult\GATHER_REASONS::interDigitTimeout)) {
      // our speech variable
      // is merely Title casing
      // our column.
      $titled = titlecase($currentSequence); 
      $speechVariable = "voiceReminder" . $titled;
      $maxDigits = "voiceReminder" . $titled . "Digits";

      // Some introspection here
      // where the gather text becomes
      // the same and the state should not change
      $gather = new Catapult\Gather(array(
        "maxDigits" => $application->$maxDigits,
        "interDigitTimeout" => 10,
        "prompt" => array(
          "speech" => $application->$speechVariable,
          "voice" => $application->voiceReminderVoice,
          "gender" => $application->voiceReminderVoiceGender,
         ))
      );

      exit(1);

    }


    // Recommended 
    //
    // we needed the terminating
    // digit as part of our last
    // message
    //
    // Terminating-Digits are useful
    // when initiating an event based
    // speech where we need the user's
    // input

    if ($state == Catapult\GATHER_STATES::completed && ($reason == Catapult\GATHER_REASONS::terminatingDigit || $finished)) {

      // use our user's input 
      // we will only deal with 
      // certain sequences on terminating
      //
      // speech and thanks should
      if ($currentSequence == "speech") {
        $call->speakSentence(array(
            "sentence" => $application->voiceReminderSpeechThanks, 
            "gender" => $application->voiceReminderVoiceGender,
            "voice" => $application->voiceReminderVoice
        ));

        // our digits will not
        // be given for this context
        // and we need to set them
        // to empty
        $digits = $application->voiceReminderTerminatingDigits;

        sleep(3);

        $started = true;
      } 
    }
    // Recommended 
    //
    // check both the gather's
    // state and our currentSequence's
    // the Gather should be complete
    // and our sequence should be NULL
    //
    // it is also best we check the reason
    // was teminating digit as this is
    // what the program needs 
    //
    // Implementors Note:
    //
    // Started flag will allow us to perform 
    // multiple speeches in parallel sometimes needed
    // when started is inputted it should contradict
    // not be a max-digits gather
    
    if ($state == Catapult\GATHER_STATES::completed && ($reason == Catapult\GATHER_REASONS::maxDigits || $started)) {


      // For this application we are 
      // asking the user to enter the terminating
      // digit which is part of its flow.  
      // so we will need to check the reason of the 
      // gather ending

      updateRow(sprintf(
        "UPDATE %s SET %s = '%s' WHERE call_id = '%s';", $application->applicationDataTable, $currentSequence, $digits, $call->id));



      // Now get our next
      // rule which is by using
      //

      $currentSequence = getNextCol($currentSequence);

      // our speech variable
      // is merely Title casing
      // our column.
      $titled = titlecase($currentSequence); 
      $speechVariable = "voiceReminder" . $titled;
      $maxDigits = "voiceReminder" . $titled . "Digits";

      $gather = new Catapult\Gather($call->id, array(
        "maxDigits" => $application->$maxDigits,
        "terminatingDigit" => $application->voiceReminderTerminatingDigits,
        "interDigitTimeout" => 10,
        // Recommended 
        //
        // as per our last prompt a Gather's prompt
        // can continue our speech.
        // This is preferred when dealing  
        // with sequence based input
        "prompt" => array(
          "sentence" => $application->$speechVariable,
          "voice" => $application->voiceReminderVoice,
          "gender" => $application->voiceReminderVoiceGender,
        )
      ));


      // Important
      //
      // Our last and final step is to hangup once the
      // call's reminder has been
      // set. By this time we should
      // ensure we have the recording, and date
      //
      if ($currentSequence == "thanks") {

        // Call should wait
        // until our last message
        // is emitted. Then we can
        // safely hangup
        sleep(7);
        $call->hangup();
      }

      exit(1);
    }
  }
}

if ($timeoutCallEvent->isActive()) {
  // Recommended
  //
  // treat a timeout
  //
  $call = new Catapult\Call($timeoutCallEvent->callId);
  $call->hangup();
}

if ($errorCallEvent->isActive()) {
  // Recommended
  //
  // treat any errors for
  // this call
}

if ($hangupCallEvent->isActive()) {
  // Recommended
  //
  // user has hung up
  // usually we should clean up
  // our demo preserves the demo
  // so we will not here
}


?>
