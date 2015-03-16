<?php 

require_once(__DIR__."/../config.php");
require_once(__DIR__."/config.php");

// IMPORTANT
// this provides rules for checking your
// applicastion has been setup properly 
// for the application's implementation you
// should look in callback.php

$client = new Catapult\Client;
$simulations = getQuery(sprintf("SELECT * FROM %s; ", $application->applicationTable));
$simulationsData = getQuery(sprintf("SELECT * FROM %s; ", $application->applicationDataTable));
$simulationsCnt = getCount(sprintf("SELECT COUNT (*) as count FROM %s; ", $application->applicationTable));

$status = "";

// Validation 1 
//
// check if the number is valid
// according
//
$phoneNumber = new Catapult\PhoneNumber($application->keypadSimulatorNumber);
if (!$phoneNumber->isValid()) {
  $message = $application->keypadSimulatorNumber . " is not a valid number";
}

// Validation 2
// 
// we should also check
// if the number is in our
// Catapult list
$phoneNumbers = new Catapult\PhoneNumbersCollection;
$phoneNumbers = $phoneNumbers->listAll(array("size"=>1000))
                             ->find(array("number" => $application->keypadSimulatorNumber));

// empty would mean no matches
// and we can safely say the number is 
// not one of ours
if ($phoneNumbers->isEmpty()) {
  $message = $application->keypadSimulatorNumber . " is not listed under your catapult account" ;
}

// Recommended Validation
//
// Check if the number is enabled
// when it isn't we won't be able
// to do anything so this is usually
// a good rule
if (is_object($phoneNumbers->first()) && $phoneNumbers->first()->numberState !== "enabled") {
  $message = $application->keypadSimulatorNumber . " is not enabled.";
}

// Validation 3
//
// for the keypad simulator
// we need to make sure our
// sequences are numerical
$keys = get_object_vars($application->keypadSimulatorNumbers);
foreach ($keys as $k =>  $key) {
  if (!is_numeric($k) && $k !== "speech") {
    $message = sprintf("%s is not a valid keypad key for this simulator.", $k);
  }
}

// At this point if no messages
// were formed the application is
// ready
if (!isset($message)) {
  $status = "success";
  $message = "You can run the keypad simulator use number: " . $application->keypadSimulatorNumber;
}


?>
