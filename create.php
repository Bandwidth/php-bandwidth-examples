<?php

// SIP call processing using Bandwidth.com SIP
//
//
// this file  implements what we need in order to use
// domains and endpoints. Here we will create the following:
//
// 1. Catapult User
// 2. Catapult endpoint and domain
//
// we also need to check if any of this
// has already been done or not.
// we can do this using our default domain

define("DEFAULT_APPLICATION_NAME", "Example: SIP Client Application");
define("DEFAULT_DOMAIN_NAME", "Default-Domain2");
define("DEFAULT_DOMAIN_DESCRIPTION", "a unique description");
define("DEFAULT_ENDPOINT_DESCRIPTION", "a unique endpoint description");
define("DEFAULT_AREA_CODE", "469");
define("DEFAULT_ACCOUNT_NAME", "");

require_once(__DIR__."/config.php");

function createIfNeeded($username='', $password='', $domainName=DEFAULT_DOMAIN_NAME, $domainDescription=DEFAULT_DOMAIN_DESCRIPTION, $endpointDescription=DEFAULT_ENDPOINT_DESCRIPTION, $areaCode=DEFAULT_AREA_CODE, $applicationName=DEFAULT_APPLICATION_NAME) {
  try {
    Catapult\Credentials::setPath(__DIR__);
    $client = new Catapult\Client;
    $account = new Catapult\Account; 

    // first let's retrieve or create
    // a new application
    //
    // TODO: currently supports a single user
    // this should be multiuser based
    $applications = new Catapult\ApplicationCollection;
    $applications = $applications->listAll(array("size" => 1000))->find(array(
       "name" => $applicationName
    ));
    if ($applications->isEmpty()) {
      // standards for creating
      // this application provided
      // we're using SIP
      //
      // callbackMethod 
      //
      $application = new Catapult\Application(array(
        "name" => $applicationName, 
        "callbackHttpMethod" => "POST",
        // our callbacks will
        // be triggered to this
        // page
        //
        // as we are using PHP_SELF
        "incomingCallUrl" => "http://" . $_SERVER{"HTTP_HOST"} . preg_replace("/index\.php/", "", $_SERVER{'PHP_SELF'} . "callback/"),
        "autoAnswer" => TRUE
      ));
    } else {
      $application = $applications->last();
    }

    file_put_contents("./testing1", json_encode(file_get_contents("php://input")));
    // now we can check 
    // for domains under this
    // account which should
    // be similar to the user
    // check
    $domains = new Catapult\DomainsCollection;
    $domains = $domains->listAll(
      array(
          "size" => 1000
      )
    )->find(array(
      "name" => $domainName
    ));
    

    // have we created a domain     
    // for this name, if we have
    // lets use it
    if (!$domains->isEmpty()) {
      // application was 
      // already ran with this
      // no need to create
      $domain = $domains->last();      

      // due to whatever reason (an HTTP request failing)
      // or a client exiting prematuraly we should still
      // check if this endpoint was made
      // we can do this by listing all
      // the endpoints for this domain

      $endpoint = $domain->listEndpoints()->find(array("name"=>$username));
      if ($endpoint->isEmpty()) {
        // here we create
        // the endpoint

        $endpoint = new Catapult\Endpoints($domain->id,array(
          "name"=> $username, 
          "applicationId" => $application->id,
          "description" => $endpointDescription,
          "credentials" => array(
            "username" => $username,
            "password" => $password
          )
        ));
      }  else {
        // point to the last
        // endpoint
        //
        $endpoint = $endpoint->last();
      }
    } else {
      // create our default
      // domain
      //
      // The name on this easily identifiable
      // is our definition, which can be
      // found at the top of this program
      $domain = new Catapult\Domains(array(
         "name" => $domainName,
         "description" => $domainDescription
      ));
      $endpoint = new Catapult\Endpoints($domain->id,array(
        "name"=> $username, 
        "applicationId" => $application->id,
        "credentials" => array(
          "username" => $username,
          "password" => $password
        )
      ));
    }
    
    // By now we should be able to create
    // our default domain which is labeled
    // according to our defaults

    // we now need to allocate one number
    // using our default area code
    // 
    // when an application already has a phone 
    // number this process is not needed 

    $phoneNumbers = new Catapult\PhoneNumbers;
    // when we already have a number for
    // this application use that
    $phoneNumbersColl = new Catapult\PhoneNumbersCollection;
    $numbers = $phoneNumbersColl->listAll(array("size" => 1000));
    $newNumber = true;
    foreach ($numbers->get() as $number) {
      // as of this application
      // to get the application id
      // we need to extract its id from the url
      // {catapult_api_url}/applications/{id}
      if (isset($number->application)) {
        if (preg_match("/\/(a-.*)$/",$number->application, $m)) {
          $applicationId = $m[1];
          if ($applicationId == $application->id) {
            $newNumber = false; 
            $phoneNumber = $number;
            break;
          }
        }
      }
    }
    if ($newNumber) {

      $phoneNumber = $phoneNumbers->listLocal(array(
        "areaCode" => $areaCode
      ))->last();
      $phoneNumber->allocate(array(
         "number" => $phoneNumber->number,
         "applicationId" => $application->id
      ));  
    } 

    return array(
      "endpoint" => $endpoint->toArray(),
      "number" => $phoneNumber->number,
      "domain" => $domain->toArray()
    );

  } catch (CatapultApiException $e) {
    // something happened
    // we should check
    
    $error = $e->getResult();
    echo json_encode($error);     

    return false; 
  }
}

?>
