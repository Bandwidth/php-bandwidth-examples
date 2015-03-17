<?php
require_once(__DIR__."/../config.php");
require_once(__DIR__."/config.php");

// Transfer Calls
// This is a simple implementation of call transferring.
//
// more accuratly described here:
// https://catapult.inetwork.com/guides/call-forwarding
//
//
//
// Tip:
// You can access Answer Call and Incoming Call Events directly
// with AnswerCallEvent and IncomingCallEvent. 
// once you have the call object you can  use transfer/1 
//
// By default we listen to applications with auto incoming
// calls answering set to true.

$client = new Catapult\Client;
// if you're using manual incoming calls
// comment this out
$inboundCallEvent = new Catapult\AnswerCallEvent;
// uncomment this if your using
// manual calling
//$inboundCallEvent = new Catapult\IncomingCallEvent;
$errorCallEvent = new Catapult\ErrorCallEvent;
$hangupCallEvent = new Catapult\HangupCallEvent;
$timeoutCallEvent = new Catapult\TimeoutCallEvent;
$rejectCallEvent = new Catapult\RejectCallEvent;

try {

  // Step 1:
  //
  // Handle incoming calls these
  // all need to be coming from the

  if ($inboundCallEvent->isActive()) {

     $call = new Catapult\Call($inboundCallEvent->callId);

     if ($inboundCallEvent->to == $application->listeningNumber) { 

       // Important
       //
       // this is for IncomingCallEvent only
       // we need to accept it
       // before we transfer
       if ($call->state == Catapult\CALL_STATES::started) {
          $call->accept();
       }


       // we have the call object now
       // and we can transfer
       $call->transfer($application->transferNumber);

       // Optional 
       //
       // log it in the database
       $date = new DateTime;
       addRecordBasic($application->applicationTable, array($call->from, $call->to, $call->state, $date->format("Y-m-d")));
     }
  }

  if ($errorCallEvent->isActive()) {
    // Recommended
    //
    // treat any errors
  }
  if ($hangupCallEvent->isActive()) {
    // Recommended
    //
    // treat any hang ups
  }

  if ($timeoutCallEvent->isActive()) {
    // Recommended
    //
    // treat timeouts
    $call = new Catapult\Call($timeoutCallEvent->callId);
    $call->hangup();
  }

  if ($rejectCallEvent->isActive()) {
    // Recommended
    //
    // treat a rejected call
  }

} catch (CatapultApiException $e) {

// Recommended:
//
// logging the other data can prove
// to be of benefit especially when debugging
// things like this as we are uncertain when an error
// may arise

// Implementors Note:
// Usually the Catapult logger
// will tell us what happened here 
// you can add an even more comprehensive message
// using this exception

}

?>
