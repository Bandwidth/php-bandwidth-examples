<?php

require_once("../php-bandwidth/source/Catapult.php");
require_once("./config.php");
// NOTE: This is for those who need to send themselves a message!
// it will use your last number send to the number in auto replies.
// Result, would mock the same functionality as phsically sending the message 

$client = new Catapult\Client;
$phoneNumbers = new Catapult\PhoneNumbers;

// send our message
$message= new Catapult\Message(array(
  "from" => $phoneNumbers->listAll()->last()->number,
  "to" => $application->autoReplyNumber,
  "text" => $application->autoReplyText,
));


$messagesFrom = new Catapult\Message;

//printf("Ok. We sent %s a message, the event will fire and you should have the result listed in: %s", $application->autoReplyNumber, "./index.php");
header("location: ./index.php");
?>
