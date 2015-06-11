<?php

// base setup of application
// we don't need much here
// as we are just listing transactions
//
require_once(__DIR__."/config.php");

Catapult\Credentials::setPath(__DIR__."/..");
$client = new Catapult\Client;
$status = "success";
$message = "You have start listing your Catapult transactions";
$headers = array(
   "number",
   "transactionType", // credit, charge, etc
   "productType", // sms-in, mms-out, etc
   "units",
   "amount" // a unit charge
);
$transactionTypes = array(
  "credit",
  "charge",
  "payment",
  "auto-recharge"
);

?>
