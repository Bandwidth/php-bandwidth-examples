<?php


require_once(__DIR__."/db.php");
require_once(__DIR__."/functions.php");
require_once(__DIR__."/vendor/autoload.php");
/** set the credentials path without this it would use the Catapult source tree! **/

$MOCK = TRUE;
/** defines the mininum account balance needed to run an application **/
define("CATAPULT_MININUM", 0.08);


/**
 * provide a mock
 * mode for testing
 */
function isMockMode() {
  global $MOCK;
  return $MOCK;
}
/**
 * verify if our user
 * is connected
 * do this by looking at the account
 */
function isConnected() {
  $connected = true; 

  require_once(__DIR__."/vendor/autoload.php");

  try {
     Catapult\Credentials::setPath(__DIR__);
     $client = new Catapult\Client;

     $account = new Catapult\Account;
     $account->get();
  } catch (CatapultApiException $e) {
    $connected = false;
  }

  return $connected;
}

/**
 * helper most functions
 * should preserve the UI
 */
function isUIMode() {
  global $UI;
  return $UI;
}

function setUI($bool) {
  global $UI;
  $UI = $bool;
}
