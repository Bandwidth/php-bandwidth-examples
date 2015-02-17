<?php 

require_once("../config.php");
require_once("config.php");

$client = new Catapult\Client;
$query = $db->query("SELECT * FROM `Keypad Simulator`; ");
$keypadCnt = count($query) - 1;

$status = "";
$phoneNumber = new Catapult\PhoneNumber($application->keypadSimulatorNumber);
if (!$phoneNumber->isValid()) {
  $message = $application->keypadSimulatorNumber . " is not a valid number";
}

$phoneNumbers = new Catapult\PhoneNumbersCollection;
$phoneNumbers->listAll();
$phoneNumbers->find(array("number" => $application->keypadSimulatorNumber));

if ($phoneNumbers->isEmpty()) {
  $message = $application->keypadSimulatorNumber . " is not a valid number";
}


if (!isset($message)) {
  $status = "success";
  $message = "You can run the keypad simulator";
}


?>
