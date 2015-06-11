<?php

require_once("../config.php");
require_once("./config.php");
Catapult\Credentials::setPath(__DIR__."/..");
$client = new Catapult\Client;
echo var_dump($application);
$call = new Catapult\Call(array(
  "from" => $application->conferenceInitiateNumber,
  "to" => $application->conferenceFromNumber
));
?>
