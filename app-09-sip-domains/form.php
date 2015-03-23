<?php

require_once("../config.php");
require_once("./config.php");
// Form processing for SIP/Domains
// using Try catch here is exceptional
// because it will allow us to continue
// execution while updating our warnings
// or successes


$client = new Catapult\Client;
$domainsResult = "";
$domainsSuccess = "false";
$endpointsResult = "";
$endpointsSuccess = "false";

// Recommended
//
// we should usually 
// only treat POST
if ($_SERVER['REQUEST_METHOD'] == "POST") {

    
  if (isset($_REQUEST['domainsName']) && $_REQUEST['domainsName'] != "") {

    // This is a domains requests.
    // domain setup is pretty straight forward
    // -- we just need a name and description.
    // we will however use a try catch to make things easier


    try {
      $domainName = $_REQUEST['domainsName'];
      $domainDescription = $_REQUEST['domainsDescription'];
      $domain = new Catapult\Domains;

      $domain->create(array(
        "name" => $domainName,
        "description" => $domainDescription
      ));

      // we have created the domain
      if ($domain->id) {
         $domainsSuccess = "true"; 
         $domainsResult = "Your domain was created";
      }

    } catch (CatapultApiException $e) {

      // warn in error
      // the domain could
      // not be created this can be due to 
      // 1. Domain name exists
      // 2. Domain name invalid
      // 3. User account unable to create domains

      $errors = array();
      $result = $e->getResult();
      $errors[] = "Unable to create Domain";
      //$errors[] = "Category: " . $result->category;
      $errors[] = "Message: " . $result->message;
      foreach ($result->details as $detail) {
         $errors[] = sprintf("Line: %s, value: %s", $detail->name, $detail->value);
      }

      $domainsResult = implode("<br />", $errors);
    }


    // go back to our application page
    route(
      sprintf("./?domains-result=%s&domains-success=%s",$domainsResult,$domainsSuccess)
     );
  }

  if ( isset($_REQUEST['endpointName']) && $_REQUEST['endpointName'] != "") {


    // It's an endpoints request. 
    // For this we need more information
    // *domain
    // *name
    // *password
    // *realm
    //
    //
    // try to create an endpoint
    // this should check if that
    // domain exists first
    //

    try {

    

      $domain = new Catapult\Domains($_REQUEST['endpointDomain']);
      $credentials = new Catapult\EndpointsCredentials(array(
        "username" => $_REQUEST['endpointUsername'],
        "password" => $_REQUEST['endpointPassword'],
        "realm" => $_REQUEST['endpointRealm']
      ));

      

      $endpoint = new Catapult\Endpoints($domain->id,array(
        "name" =>  $_REQUEST['endpointName'],
        "description" => $_REQUEST['endpointDescription'],
        "credentials" => $credentials
      ));


      // we have created the endpoint
      if ($endpoint->id) {

        $endpointsSuccess = "true";
        $endpointsResult  = "The endpoint was created successfully";

      }

    } catch (CatapultApiException $e) {

      // warn in error this
      // be the following errors
      // Above: domain errorss
      // Endpoints:
      // endpoint name invalid
      // endpoint description invalid
      // endpoint sip uri invalid
      // endpoint credentials invalid


      // A quick demo on parsing the result
      // from a Catapult response it can
      // be used in multiple occasions

      $errors = array();
      $result = $e->getResult();
      $errors[] = "Could not create endpoint";
      //$errors[] = "Category: " . $result->category;
      $errors[] = "Message: " . $result->message;
      //$errors[] = "Code: " . $result->code;
      foreach ($result->details as $detail) {
         $errors[] = sprintf("Line: %s, value: %s", $detail->name, $detail->value);
      }

      $endpointsResult = implode("<br />", $errors);
      
    }

    // go back to our application page

    route(
      sprintf( "./?endpoints-result=%s&endpoints-success=%s",$endpointsResult,$endpointsSuccess)
    );
  }
  

}

?>
