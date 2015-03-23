<?php
require_once(__DIR__."/../config.php");
require_once(__DIR__."/config.php");

// This will intiiate a SIP 
// call to the given domain endpoint
// it should use the request variables
// that the interface passes
// it 

$client = new Catapult\Client;

$domain = new Catapult\Domains($_REQUEST['domainId']);
$endpoint = new Catapult\Endpoints($domain->id, $_REQUEST['endpointId' . '_'. $domain->id]);

$numbers = new Catapult\PhoneNumbersCollection;

$use = $numbers->listAll(array("size"=>1000))->last()->number;
// now take the sipUri
// and make a call to it
$call = new Catapult\Call(array(
  "from" => $use,
  "to" => $endpoint->sipUri
));


// add a sleep, results will
// not be guarenteed to be there

sleep(3);
route(sprintf("./?message=%s", "We have initiated the SIP call and are waiting ona  response"));

?>
