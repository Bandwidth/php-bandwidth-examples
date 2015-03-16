<?php

require_once("./config.php");
$client = new Catapult\Client;
$calls = new Catapult\CallCollection;

foreach ($calls->listAll()->get() as $call) {
  if ($call->state == "started" || $call->state == "active") {
    $call->hangup();
  }
}
?>
