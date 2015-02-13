<?php
require_once("../php-bandwidth/source/Catapult.php");
// include both configs
require_once(realpath(__DIR__."/config.php"));
require_once(realpath(__DIR__"../config.php"));

$client = new Catapult\Client;

$event = new Catapult\Event;
if ($event->eventType == 'answer') {
  /**
   * Step 1:
   * Create a gather.
   * This will be used for the user's
   * dialed numbers. We will get to this
   * in step two
   *
   */
   $gather = new Catapult\Gather($event->id);

   $gather->create();

} elseif ($event->eventType == 'dtmf') {

  /**
   * Step 2:
   * The user has entered digits we need
   * to find what has been using our gather
   * 
   */

  $gather = new Catapult\Gather($event->id, $event->gatherId);

   /**
    * Speak a sentence
    *
    * Voice for this is defined
    * in ./application.json
    *
    */

   $call = new Catapult\Call($event->id);
   $voice = new Catapult\Voice($application->voiceReminderVoice);

   $call->speakSentence(array(
      "voice" => $voice,
      "sentence" => $application->voiceReminderSentence
   ));

   // it is now time to store the 
   // result in our database

  $date = new DateTime;

  addRecord(array($event->from, $event->to, $gather->digits, $date->format("Y-M-D H:i:s")));

} else if ($event->eventType == 'speak') {

  /**
   * Step 3.
   * The user said something. This means 
   * we should hangup the call, cleanup 
   *
   *
   *
   *
   */

   $call = new Call($event->id);

   $call->hangup();

}

}
?>
