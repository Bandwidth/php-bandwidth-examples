<?php
require_once("../php-bandwidth/source/Catapult.php");
require_once("../config.php");
require_once("./config.php");

/**
 * Sets up MessageEvent to listen to Catapult's callback
 * When we don't receive anythingwe should simply wait 
 * with this. This script can run seperatly or inline. Here,
 * it is inline.
 *
 * Important: make sure you've correctly configured this number with 
 * callBackUrl in ./application.json
 *
 * also setup ./event.php with in your Catapult callback
 */
$client = new Catapult\Client;

// get all the messages that we're considered auto replies
// we will list these for reference
// considerably this will get all the 'direction' outgoing with
// our auto reply text. So make sure its something unique.

// uses sqlite in doing
$messages = $db->query("SELECT * FROM `" . $application->applicationName . "`;");
$messagesCnt = count($messages) - 1; // SQLite returns + 1


// This is a way to use the API over our SQLite integration
// it would essentially do the same thing.
//$messages = new Catapult\Message;
// $messages->listAll(find);

// perform some introspection here
// for the examples we need to make sure
// the numbers defined are valid 
// 
// we can do this easily using Catapult's
// PhoneNumber class it will ensure our number
// is in E.164 format

$PhoneNumber =   new Catapult\PhoneNumber($application->autoReplyNumber);

if (!$PhoneNumber->isValid()) {
  $message = "Your autoReplyNumber is not valid.. it needs to be E.164 format";
}

// also make sure the number is one of the Catapult
// numbers.
// 
// we can do this by listing all our numbers
$PhoneNumbers = new Catapult\PhoneNumbersCollection;

$PhoneNumbers->listAll();

$PhoneNumbers->find(array("number" => $application->autoReplyNumber));

if ($PhoneNumbers->isEmpty()) {
  $message = "The number you entered is not listed in your Catapult account";
}


// finally check if our account balance is enough
// for this application
//
// it should be good if we have enough for
// one run
$account = new Catapult\Account;
if (!($account->get()->balance >= CATAPULT_MININUM)) {
  $message = "Your account balance is too low to run this application";
}

// no errors, application should be
// in good state
if (!isset($message)) {
  $message = "We are still waiting on a message!, please send one to " . $application->autoReplyNumber;
  $status = 'success';
}

?>
