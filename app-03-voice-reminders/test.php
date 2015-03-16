<?php
require_once("../config.php");





$row = getRows("SELECT * FROM `Voice Reminders Data`; ");
echo var_dump($row);
?>
