<?php

require_once(__DIR__."/../config.php");
require_once(__DIR__."/config.php");
// This will initiate a call on you behalf, so you
// can get this example working quicker.  

$cli = new Catapult\Client;
$phoneNumbers = new Catapult\PhoneNumbers;
$calls=new Catapult\Call;
$call = new Catapult\Call(array(
  "from" => $phoneNumbers->listAll()->last()->number,
  "to" => $application->listeningNumber,
));

// To display the record in the listing
// we will need a small timeout
// this is not always guarenteed to work, the result
// however will either be active  or transferred
//
sleep(3);

route("./index.php");
?>
