<?php

// This would hypotheitically perform
// the call reminder once they have
// been set, this program is not included
// as an automatic runtime and will need to
// be activated seperatly, to do so you will
// need to run 
//
// php /app-03-voice-reminders/initiate.php 

require_once(__DIR__."../config.php");
require_once(__DIR__."./config.php");

$rows = getRows(sprintf("SELECT * FROM `%s`; ", $application->datatable));
foreach ($rows as $row) {
  $call = new Catapult\Call($row['callId']);

  // let's initiate a new call
  // this call will be to remind
  // the user of his initially set
  // reminder
  //
  // Implementors Note:
  // we will use the same number that
  // was dialed in for this, you can
  // however set a seperate number
  // for this
  $callReminder = new Catapult\Call(array(
      "from" => $application->voiceReminderNumber,
      "to" => $call->from
  ));

}
?>
