<?php
require_once(__DIR__."/../config.php");
require_once(__DIR__."/config.php");

// Basic Voice Mail
//
// this application implements the approach 
// described here:
//
// https://catapult.inetwork.com/docs/guides/voicemail/
//
//
// On success will provide a listing of voice mail recordings
// which can be then used with Catapult's Media object

$client = new Catapult\Client;
// By default we have set incoming calls to
// auto. If you plan on using the manual version
// you will need to comment this out
$inboundCallEvent = new Catapult\AnswerCallEvent;
// uncomment if your using the manual 
// version.
//$inboundCallEvent = new Catapult\IncomingCallEvent;
$speakCallEvent = new Catapult\SpeakCallEvent;
$playbackCallEvent = new Catapult\PlaybackCallEvent;
$hangupCallEvent = new Catapult\HangupCallEvent;
$timeoutCallEvent = new Catapult\TimeoutCallEvent;
$errorCallEvent = new Catapult\ErrorCallEvent;
$dtmfCallEvent = new Catapult\DtmfCallEvent;
$recordingCallEvent = new Catapult\RecordingCallEvent;

if ($inboundCallEvent->isActive()) {

   // Step 01.
   // 
   // Our call has been answered
   // This means we should play a voice
   // with the text defined in ./application.json
  $call = new Catapult\Call($inboundCallEvent->callId);
  $voice = new Catapult\Voice($application->voicemailVoice);

  // Recommended 
  // 
  // when using a manual
  // approach we will need to
  // accept the call
  if ($call->state == Catapult\CALL_STATES::started) {
    $call->accept();
  }   
  

  // Recommended
  //
  // this speech will instruct the user
  // to leave voicemail.
  $call->speakSentence(array(
     "voice" => $voice,
     "sentence" => $application->voicemailSentence,
     "gender" => $application->voicemailGender
  ));
  sleep(5);

  $call->playAudio($application->voicemailFile);

  // Important
  //
  // make sure we update
  // our recordingEnabled
  // this will allow us to 
  // make a recording
  //
  // Implementors Note: for best results
  // try to enable recording as soon as it 
  // is needed this helps in the recording quality
  // and keeping it shorter

  $call->update(array(
    "recordingEnabled" => true
  ));


  // Important
  //
  // for display purposes
  // we will be storing the data
  // in our table 
  $date = new DateTime;
  addRecordBasic($application->applicationTable, array($inboundCallEvent->from, $inboundCallEvent->to, $call->id, $date->format("Y-m-d"))); 
  // We will be using this table
  // to store all our data
  addRecord($application->applicationDataTable, array($inboundCallEvent->callId, 1), array("call_id", "initiated"));
} 

if ($playbackCallEvent->isActive()) {

   // Step 03.
   // 
   // Our playback event has been trigged
   // this means we can start our audio recording

  $call = new Catapult\Call($playbackCallEvent->callId);

}

if ($hangupCallEvent->isActive()) {

   // Step 04.
   //
   // The call has been hanged up
   // When this happens our recording should be almost
   // ready
   //
   // we don't need to add code here.

}

if ($errorCallEvent->isActive()) {
   // Recommended 
   // 
   // treats errors for this
   // call 
   // 
   // we will log it here to our error database table 
   //
   // Implementors Note:
   // Please handle event errorCallEvent in all applications
   // as it can make debugging far easier

}

if ($timeoutCallEvent->isActive()) {
   // Recommended
   //
   // treats timeoutCallEvent
   // when a timeout occurs we will warn
   // the user to continue
   //
   // Implementors Note:
   // You should handle this in all your applications
   // for QoS. It does not only serve purpose in this example 

  $call = new Catapult\Call($timeoutCallEvent->id);
  $voice = new Catapult\Voice($application->voicemailVoice);  
  
  $call->speakSentence(array(
    "voice" => $voice,
    "sentence" => $application->voicemailSentence,
    "gender" => $application->voicemailGender
  ));

  sleep(5);
  $call->hangup();

}

if ($dtmfCallEvent->isActive()) {
  // Recommended
  //
  // for best quality we should also
  // provide a digit to leave
  // the recording. Once we hangup
  // the recording will be saved
  // and accessible.

  $call = new Catapult\Call($dtmfCallEvent->callId);
  $digit = $dtmfCallEvent->dtmfDigit;

  if ($digit == $application->voicemailTerminateDigit) {
    $call->speakSentence(array(
      "sentence" => $application->voicemailThanks,
      "voice" => $application->voicemailVoice,
      "gender" => $application->voicemailGender
    ));

    sleep(5);

    $call->hangup();
  } else {
    // Optional
    //
    // check when our input
    // is not valid output
    // speech accordingly

    $call->speakSentence(array(
      "sentence" => $application->voicemailInvalid,
      "voice" => $application->voicemailVoice,
      "gender" => $application->voicemailGender
    ));


  }

}


if ($recordingCallEvent->isActive()) {

   // Step 05.
   //
   // Once a recording state is complete we are ready to 
   // fetch it.


  $call = new Catapult\Call($recordingCallEvent->callId);
  $recording = new Catapult\Recording($recordingCallEvent->recordingId);
  if ($recordingCallEvent->status == Catapult\RECORDING_STATUSES::complete) {
    // Recording is complete
    // this means we can get the
    // the media file 
    // and save it in our datbase
   
    $media = $recording->getMediaFile();


    // Here we will use a custom
    // naming convention to better
    // identify our Catapult files
    // they, afterwards store on 
    // on the server
    //
    $date = new DateTime;
    $mediaName = sprintf("%s-%s-%s.wav", $application->voicemailIdentifier,str_replace("+","",$call->from),$date->format("M-d_H-i"));
    // Optional
    //
    // while this is optional we visit 
    // Catapult's media files in this application
    // thus will make use of it's store
    // will show how to store locally
    //
    // our files will be labeled as the 
    // recordings name and be set in the database
    $file = __DIR__ . "/data/" . $mediaName;
    $media->store($file);

    // This application 
    // will demonstrate how to reupload
    // to catapult servers with a different
    // name
    //
    // we will name our media something unique
    // so that we can later identify it
    //
    // Implementors Note:
    // 
    // if you want to setData for the media
    // in place of using a file on your system please use
    // media->setData this will perform the same
    // steps

    // this example will append the call from
    // to the voicemailIdentifier
    //
    // with the date and time, reuploading onto
    // Catapult
    $newMedia = new Catapult\Media;
    $newMedia->create(array(
        "mediaName" => $mediaName,
        "file" => $file, // use our previously made object
    ));

    // Recommended
    //
    // we can now unlink this from our files
    // 
    // note:
    // for demo purposes we will keep it
    //unlink($file);

    // Recommended
    //
    // store reference to the recording in our database
    updateRow(sprintf("UPDATE %s SET media_name  = '%s' WHERE call_id = '%s'", $application->applicationDataTable, $mediaName,  $call->id));
    
  } else if ($recording->state == Catapult\RECORDING_STATUSES::complete) {
    // Recommended
    //
    // treating errorneous recordings can also 
    // be good for logging. we will
    // add this to our datatable
    //
    // This demo does not handle this.
  }
  
}
?>
