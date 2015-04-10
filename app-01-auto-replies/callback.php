<?php

require_once(__DIR__."/../config.php");
require_once(__DIR__."/config.php");


// SMS Auto Reply Callback
//
// This provides a simple
// implementation for SMS
// auto replies

// http://ap.bandwidth.com/docs/how-to-guides/sms-auto-reply/
//
// You can access messageEvents
// directly with Catapult\MessageEvent
// Afterwards construct a new message
// to the sender

$client = new Catapult\Client;
try {
  // Step 1.
  //
  // look at our message and reply
  // back to it. states
  // need to be sent additionally
  // check if its incoming 

  // Events let us describe
  // what we want. In this case
  // we will only grant incoming messages
  
  // Implementors Note:
  // 
  // The list of constants available
  // for messages comes in handy 
  // we can use this when evaluating
  // the message event
  $messageEvent = new Catapult\MessageEvent(array(
    "direction" => Catapult\MESSAGE_DIRECTIONS::in
  ));


  // Important
  //
  // we don't want to message ourselves
  // this can cause unbreakable an
  // sequence unless treated
  if ($messageEvent->from == $application->autoReplyNumber) {
    exit(1); 
  }

  // Important
  // we need to check if this event
  // is active and only respond accordingly
  if ($messageEvent->isActive()) {
    // Step 2.
    //
    // initiate the reply message
    // which uses the text defined
    // in application.json

    // Recommended
    //
    // you should probably mock
    // the actions when testing
    $newMessage = new Catapult\Message(array(
      "from" => $application->autoReplyNumber,
      "to" => $messageEvent->from,
      "text" => $application->autoReplyText
     ));

    // Optional
    // 
    // insert it in the database
    // This is internal for the application
    $date = new DateTime;
    addRecordBasic($application->applicationTable, array($application->autoReplyNumber, $messageEvent->from, $application->autoReplyText, $date->format("Y-M-D H:i:s")));

  }

} catch (CatapultApiException $e) {
  // Recommended
  //
  // we can even provide
  // a more verbose log message
  // here. The Catapult logger
  // will by default log to ./logs

}
?>

