<?php 
require_once(__DIR__ ."/../config.php");
require_once(__DIR__ . "/config.php");

// SIP Domains Application implementation 
//
// This application is a demo of accepting SIP domain
// based calls it will validate and perform 
// action based on your listed SIP domains
//
// Domains & Endpoints are described here:
//
// http://ap.bandwidth.com/docs/rest-api/domains-2/

// Implementors Note:
//
// while the Catapult PHP library has SIP
// validation functions, it is sometimes best
// to ensure the call is coming to an actual SIP
// domain by validating against your numbers.
// you can do this by using PhoneNumbersCollection
// or look at whether it is an 
//


Catapult\Credentials::setPath(__DIR__."/..");
$client = new Catapult\Client;
// we should only let in SIP calls

// by default we're using the manual
// approach if you are auto accepting incoming
// comment this out
$inboundCallEvent = new Catapult\IncomingCallEvent;
// uncomment if your using the auto approach
$inboundCallEvent = new Catapult\AnswerCallEvent;
$errorCallEvent = new Catapult\ErrorCallEvent;
$timeoutCallEvent = new Catapult\TimeoutCallEvent;
$hangupCallEvent = new Catapult\HangupCallEvent;

if ($inboundCallEvent->isActive()) {

  // Step 1.
  //
  // Take the incoming call and 
  // and check whether it was to a SIP
  // address. Whene it is handle it

  // incoming calls need to have
  // an SIP address
  $sip = new Catapult\SIP($inboundCallEvent->to);
  if ($sip->isValid()) {


    // Recommended
    //
    // when we're using the manual
    // approach we need to accept
    // incoming calls
    if ($call->state == Catapult\CALL_STATES::started) {
      $call->accept();
    }

    // we can proceed 
    $call = new Catapult\Call($inboundCallEvent->id);
    $call->accept();

    // alert the user he has
    // setup domains
    $call->speakSentence(array(
      "sentence" => $application->sipDomainsSetup,
      "voice" => $application->sipDomainsVoice,
      "gender" => $application->sipDomainsVoiceGender
    ));
    sleep(10);


    $date = new DateTime;
    addBasicRecord($application->applicationTable, array($call->from, $call->to, $call->to, $date->format("Y-m-d"));

    exit(1);
  }

  // when its not a SIP domains
  // call we will reject
  $call->reject();
}

if ($timeoutCallEvent->isActive()) {
  // Recommended
  //
  // a timeout occured
  // like above only treat when
  // SIP
}

if ($errorCallEvent->isActive()) {
  // Recommended
  //
  // an error occured we can log this
}

if ($hangupCallEvent->isActive()) {
  // Recommended
  //
  // a hangup occured we can log this
}

