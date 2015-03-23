<?php 

require_once(__DIR__ . "/../config.php");
require_once(__DIR__ . "/config.php");

// IMPORTANT
// this file is the base wrapper behind
// the interface. Implementors should look
// in callback.php which is the application's
// implementation. This provides rules for
// checking whether your application
// has been setup properly

$client = new Catapult\Client;

$domains = new Catapult\DomainsCollection;
$endpoints = array();

$domains->listAll(array("size" => 1000));
foreach ($domains->get() as $domain) {
  $endpoints[$domain->id] = $domain->listEndpoints();
} 

$sipCalls = getQuery(sprintf("SELECT * FROM %s; ",  $application->applicationTable));
$sipCallsCnt = getCount(sprintf("SELECT COUNT(*) as count FROM %s; ", $application->applicationTable));


$domains = $domains->toArray();
if ( isset($_REQUEST['domains-result'])) {
  $domainsResult = $_REQUEST['domains-result'];
}
if (isset($_REQUEST['domains-success'])) {
  $domainsSuccess = filter_var($_REQUEST['domains-success'],FILTER_VALIDATE_BOOLEAN);
}
if (isset($_REQUEST['endpoints-result'])) {

  $endpointsResult = $_REQUEST['endpoints-result'];
}
if (isset($_REQUEST['endpoints-success'])) {

  $endpointsSuccess = filter_var($_REQUEST['endpoints-success'],FILTER_VALIDATE_BOOLEAN);
}

if (!isset($message)) {
  $message = "You can create domains and endpoints";
  $status = "success";
}


$form = array(

  "domains" => array(
      "name" => array(
        "name" => "domainsName", 
        "placeholder" => "a domain name",
         "type" => "text"
      ),
      "description" => array(
          "placeholder" => "a domains description",
          "name" => "domainsDescription",
          "type" => "textarea"
      ),
      "Add Domain" => array(
          "type" => "submit",
          "placeholder" => ""
      )
   ),
   "endpoints" => array(
      "name" => array(
          "name" => "endpointName",
          "placeholder" => "e.g my endpoint",
          "type" => "input"
       ),
       "description" => array(
          "name" => "endpointDescription",
          "placeholder" => "e.g: my endpoints description",
          "type"=> "input"
       ),
       "sipUri" => array(
          "name" => "endpointSipUri",
          "placeholder" => "e.g an sip://user@domain.bwapp.io",
          "type" => "input"
       ),
       "username" => array(
          "name" => "endpointUsername",
          "placeholder" => "e.g a username",
          "type" => "input"
       ),
       "password" => array(
          "name" => "endpointPassword",
          "placeholder" => "6 characters",
          "type" => "input"
        ),
        "realm" => array(
          "name" => "endpointRealm",
         "placeholder" => "e.g an domainname.appname.providername.io",
          "type" => "input"
        ),
        "domainId" => array(
          "type" => "select",
          "name" => "endpointDomain",
          "field_name" => "name",
          "placeholder" => "",
          "field_id" => "id",
          "children" => $domains
        ),
        "Add Endpont" => array(
          "type" => "submit",
          "placeholder" => ""
        )

    ),
);
