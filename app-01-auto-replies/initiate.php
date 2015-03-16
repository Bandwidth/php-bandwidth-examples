<?php

require_once(__DIR__."../config.php");
require_once(__DIR__."./config.php");
// NOTE: This is for those who need to send themselves a message!
// it will use your last number send to the number in auto replies.
// Result, would mock the same functionality as phsically sending the message 

$client = new Catapult\Client;

// send our message
$message= new Catapult\Message(array(
  "from" => $application->autoReplyInitiateNumber,
  "to" => $application->autoReplyNumber,
  "text" => $application->autoReplyText,
));

//printf("Ok. We sent %s a message, the event will fire and you should have the result listed in: %s", $application->autoReplyNumber, "./index.php");

// add a sleep as the
// the info won't be available
// take some time to be available
sleep(2);
route("index.php?message=we have sent the initial message");
?>
