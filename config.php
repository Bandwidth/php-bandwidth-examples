<?php
require_once("db.php");

/** set the credentials path without this it would use the Catapult source tree! **/
Catapult\Credentials::setPath(__DIR__);

/** defines the mininum account balance needed to run an application **/
define("CATAPULT_MININUM", 0.08);

?>
