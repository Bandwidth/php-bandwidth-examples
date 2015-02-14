<?php
require_once("../php-bandwidth/source/Catapult.php");
require_once(__DIR__."/config.php");

$client = new Catapult\Client;

$event = new Catapult\Event;

if ($event->eventType == "answer") {

  /**
   * Step 01.
   *
   * Our call has been answered
   * This means we should play a voice
   * with the text defined in ./application.json
   *
   */
  $call = new Catapult\Call($event->id);
  $voice = new Catapult\Voice($application->voice);

  $call->speakSentence(array(
     "voice" => $voice
     "sentence" => $application->voiceSentence
  ));
} elseif ($event->eventType == "speak") {

  /**
   * Step 02. 
   *
   * Our user has spoken, this means its time
   * for the beep 
   *
   * Note:
   * Please make sure the audio file
   * is a fully qualified URL
   *
   */


  $call = new Catapult\Call($event->id);
  $call->playAudio(array(
     "audioFile" => $application->voicemailFile
  ));

} elseif ($event->eventType == "playback") {

  /**
   * Step 03.
   *
   * Our playback event has been trigged
   * this means we can start our audio recording
   * for the voice reminder
   */

  

} elseif ($event->eventType == "hangup") {

  /**
   * Step 04.
   *
   * The call has been hanged up
   * When this happens our recording should be almost
   * ready
   * 
   */

} elseif ($event->eventType == "complete" && $event->recordingId){

  /**
   *
   * Step 05.
   *
   * Once a recording state is complete we are ready to 
   * fetch it.
   *
   */

  $recording = new Catapult\Recording($event->recordingId);

  /**
   * Now we have a media
   * file that is on Catapult's
   * servers. The following Catapult\Media
   * object will allow you to do what's need from here
   */

  $media = $recording->getMediaFile();

  addRecord(array($event->from, $event->to, $media->file)); 
  
}
?>
