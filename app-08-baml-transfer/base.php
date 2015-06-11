<?php
require_once(__DIR__."/../config.php");
require_once(__DIR__."/config.php");

// IMPORTANT: 
// This file is the base wrapper behind
// the interface. Implementors should look in
// callback.php which is the application's 
// implementation. This provides rules for
// checking whether your application has been
// setup properly.
//


Catapult\Credentials::setPath(__DIR__."/..");
$client = new Catapult\Client;

$transfers = getQuery(sprintf("SELECT * FROM %s; ", $application->applicationTable));
$transferCnt = getCount(sprintf("SELECT COUNT(*) as count FROM %s; ", $application->applicationTable));

// check if the number is a  valid 
// one
$numbers = new Catapult\PhoneNumber($application->BaMLTransferListeningNumber);
if (!$numbers->isValid()) {
  $message = "The calling number was not valid";

}

// check if its listed under this
// catapult account
$numbers = new Catapult\PhoneNumbersCollection;
$numbers1 = $numbers->listAll(array("size"=>1000))->find(array("number" => $application->BaMLTransferListeningNumber));

if ($numbers->isEmpty()) {
  $message = "The calling number is not listed under your catapult account";
}

// Recommended
// check if our application exists
// and whether it has been
// setup with a GET callback

$applicationList = new Catapult\ApplicationCollection;
$applicationList->listAll(array("size"=>1000));
$applicationList->find(array("id" => $application->applicationId));

if ($applicationList->isEmpty()) {
  $message = "This application ID was not found under your Catapult account";
} else {
  $app = new Catapult\Application($application->applicationId);

  if ($app->callbackHttpMethod != "get") {
    $message = "Your callback method is POST, BaML transfers require GET";
  }
}

// checks for the second
// transfering number
$number2 = new Catapult\PhoneNumber($application->BaMLTransferToNumber);
if (!$number2->isValid()){
  $message = $application->BaMLTransferToNumber . " transfering number is not valid phone number";
}

$numbers2 = new Catapult\PhoneNumbersCollection;
$numbers2->listAll(array("size" => 1000))->find(array("number" =>  $application->BaMLTransferToNumber));
if ($numbers2->isEmpty()){
  $message = $application->BaMLTransferToNumber . " transfering number is not listed under this Catapult account";
}

if (!isset($message)) {
  $status = "success";
  $message = "You can make BaML call transfers using: " . $application->BaMLTransferListeningNumber;
}
?>
