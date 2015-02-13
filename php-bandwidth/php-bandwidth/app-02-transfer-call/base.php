<?php

require_once("../php-bandwidth/source/Catapult.php");
require_once('../config.php');
require_once("./config.php");

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
$calls = $db->query("SELECT * FROM `" . $application->applicationName . "`; "); 
$callCnt = count($calls) - 1; // SQLite returns - 1

$status = 'error';
$number1 = new Catapult\PhoneNumber($application->transferNumber);
$number2 = new Catapult\PhoneNumber($application->listeningNumber);

if (!$number1->isValid()) {
  $message = "The number for transferring needs to be in E.164 format";
}
if (!$number2->isValid()) {
  $message = "The number for listening needs to be in E.164 format";
}

$pn = new Catapult\PhoneNumbers;
$list = $pn->listAll();
$numbers1 = $list->find(array("number" => $number1));


if ($numbers1->isEmpty()) {
  /** this one should be a warning, the transfer to number does not need to be under the 
   * Catapult account. Given its a simple transfer **/
  $status = "warning";
  $message = "The transferring number is not setup under your Catapult account, this <b>will</b> transfer anyway";
}
$numbers2 = $list->find(array("number" => $number2));
if ($numbers2->isEmpty()) {
  $message = "The listening number is not setup under your Catapult account";
}

if (!isset($message)) {
  $message = "We are listening to transfers on " . $application->listeningNumber . ", dial in now!";
  $status = "success";
}

?>
