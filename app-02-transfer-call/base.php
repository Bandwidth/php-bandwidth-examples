<?php

require_once(__DIR__.'/../config.php');
require_once(__DIR__."/config.php");

// IMPORTANT: 
// This file is the base wrapper behind
// the interface. Implementors should look in
// callback.php which is the application's 
// implementation. This provides rules for
// checking whether your application has been
// setup properly.
//

$client = new Catapult\Client;

// get the application's calls (so far),  from 
// our database
$calls = $db->query(sprintf(
  "SELECT * FROM `%s` 
", $application->applicationTable)); 

$callCnt = getCount(sprintf(
  "SELECT COUNT(*) as count FROM `%s`", $application->applicationName
));

$status = 'error';
$number1 = new Catapult\PhoneNumber($application->transferNumber);
$number2 = new Catapult\PhoneNumber($application->listeningNumber);

// Validation 1
//
// check if our numbers are valid
if (!$number1->isValid()) {
  $message = "The number for transferring needs to be in E.164 format";
}
if (!$number2->isValid()) {
  $message = "The number for listening needs to be in E.164 format";
}


// Validation 2
// 
// check if only the listening number
// number is listed under our Catrapult
// account
$pn = new Catapult\PhoneNumbers;
$list = $pn->listAll(array("size" => 1000));
$numbers1 = $list->find(array("number" => $number1));


// Transferring number is 
// not listed
if ($numbers1->isEmpty()) {
  // this one should be a warning, the transfer to number does not need to be under the 
  // Catapult account. Given its a simple transfer
  $status = "warning";
  $message = "The transferring number is not setup under your Catapult account, this <b>will</b> transfer anyway";
}

$list = $pn->listAll();
$numbers2 = $list->find(array("number" => $number2));

// Listening number is not listed
if ($numbers2->isEmpty()) {
  $message = "The listening number is not setup under your Catapult account";
}

if (!isset($message)) {
  $message = "We are listening to transfers on " . $application->listeningNumber . ", dial in now!";
  $status = "success";
}

?>
