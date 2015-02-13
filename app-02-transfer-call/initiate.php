<?php

require_once("../php-bandwidth/source/Catapult.php");
require_once("./config.php");
// This will initiate a call on you behalf, so you
// can get this example working quicker.  

$cli = new Catapult\Client($application->bandwidthUserId, $application->bandwidthToken, $application->bandwidthTokenSecret);
$phoneNumbers = new Catapult\PhoneNumbers;
$calls=new Catapult\Call;
$call = new Catapult\Call(array(
  "from" => $phoneNumbers->listAll()->last()->number,
  "to" => $application->listeningNumber,
));

printf("We've ringed: %s, 'it' should transfer your call, and store the result in: %s", $call->to, "./index.php");
?>
