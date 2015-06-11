<?php

require_once("../config.php");
Catapult\Credentials::setPath(__DIR__."/..");
$client = new Catapult\Client;
$recordings = new Catapult\RecordingCollection;
$recordings->listAll()->last()->delete();
?>
