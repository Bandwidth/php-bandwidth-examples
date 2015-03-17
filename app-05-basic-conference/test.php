<?php

require_once("../config.php");
require_once("./config.php");
$client = new Catapult\Client;
echo var_dump($application);
$call = new Catapult\Call(array(
  "from" => $application->conferenceInitiateNumber,
  "to" => $application->conferenceFromNumber
));
?>
