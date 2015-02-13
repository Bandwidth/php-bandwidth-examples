<?php

require_once("../php-bandwidth/source/Catapult.php");
require_once("config.php");


// SMS Auto Reply Callback
// This provides a simple
// implementation for SMS
// auto replies
//
// You can access messageEvents
// directly with Catapult\MessageEvent
// Afterwards construct a new message
// to the sender
$client = new Catapult\Client;

$messageEvent = new Catapult\MessageEvent;


$newMessage = new Catapult\Message(array(
  "from" => $application->autoReplyNumber,
  "to" => $messageEvent->from,
  "text" => $application->autoReplyText
));


// insert it in the database
// This is internal for the application
$date = new DateTime;
addRecord(array($application->autoReplyNumber, $messageEvent->from, $application->autoReplyText, $date->format("Y-M-D H:i:s")));
?>

