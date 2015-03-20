<?php

require_once(__DIR__."/../config.php");
require_once(__DIR__."/config.php");

$client = new Catapult\Client;
$phoneNumbers = new Catapult\PhoneNumbersCollection;
$last = $phoneNumbers->listAll()->last()->number;
$call = new Catapult\Call(array(
  "from" => $last,
  "to" => $application->BaMLTransferListeningNumber
));

// wait for the call to be
// this process is never
// deterministic and we may or may not
// have results when we're done
sleep(3);
// go back to our primrary
// screen
route(sprintf("./index.php?message=%s", "Thank you we have initiated the BaML transfer"));
?>
