<?php
require_once("../php-bandwidth/source/Catapult.php");
require_once("config.php");

// Transfer Calls
// This is a simple implementation of call transferring.
//
// You can access Answer Call Events directly
// with Catapult\AnswerCallEvent. Afterwards use transfer/1 
// with a new call object
$client = new Catapult\Client;

try {

  $call = new Catapult\AnswerCallEvent;

  if ($call->state == 'started' && $call->direction == 'incoming') {

    $transferCall = new Call($call->id);
    $transferCall->transfer($application->transferNumber);
  }


  // log it in our SQlite. Internal only
  $date = new DateTime();
  addRecord(array($call->from, $application->transferNumber, $transferCall->state, $date->format("Y-M-D H:i:S"))); 

} catch (CatapultApiException $e) {

/**
 * Usually the Catapult logger
 * will tell us what happened here 
 * you can add an even more comprehensive message
 * using this exception
 */


}

?>
