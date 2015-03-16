<?php

require_once("./lib/php-bandwidth/source/Catapult.php");

Catapult\Credentials::setPath(__DIR__);
$client = new Catapult\Client;
$calls = new Catapult\CallCollection;
foreach ($calls->listAll()->get() as $call) {
  if ($call->state == Catapult\CALL_STATES::active) {
    $call->hangup();
  }
}
?>
