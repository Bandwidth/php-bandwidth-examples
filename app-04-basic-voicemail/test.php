<?php

require_once("../config.php");
$client = new Catapult\Client;
$recordings = new Catapult\RecordingCollection;
$recordings->listAll()->last()->delete();
?>
