<?php

require_once("./config.php");
$client = new Catapult\Client;
$calls = new Catapult\CallCollection;

$cnt = 0;


  $calls_ =  $calls->listAll(array("size" => 1000, "page" => $cnt))->get();
while (count($calls_) > 0) {
echo count($calls_);
  foreach ($calls_ as $call) {
  if ($call->state == "started" || $call->state == "active") {
    $call->hangup();
  }
  $cnt ++;
  $calls = new Catapult\CallCollection;
  $calls_ =  $calls->listAll(array("size" => 1000, "page" => $cnt))->get();
}

}
?>
