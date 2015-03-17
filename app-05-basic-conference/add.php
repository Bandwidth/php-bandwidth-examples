<?php

require_once(__DIR__."/../config.php");
require_once(__DIR__."/config.php");
//
// this will add an attendee to
// the applicationlication structure, conversly
// to just editing the file this will only
// be used within the form for application-05

$attendees = $application->conferenceAttendees;
// we should chekc if its in the current list or not
if (isset($_REQUEST['attendee']) && $_REQUEST{"attendee"} ) {
    
  if (in_array($_REQUEST{"attendee"}, $attendees)) {
    // warn here
    //
    // conference attendee was already 
    // found
    //

    die("This conference already has this member..");
  }

  // now validate the phone
  // number
  //
  // this uses the Catapult phoneNumber
  // helper which will validate a good number

  $number = $_REQUEST{"attendee"}; 
  $phoneNumber = new Catapult\PhoneNumber($_REQUEST{'attendee'}); 

  if (!$phoneNumber->isValid()) {
    // warn here as well

    die("The phone number was not valid");
  }

  $attendees[] = $number;  
  $application->conferenceAttendees = $attendees;

  // reform applicationlication.json
  // this will now use the current
  // attendees structure
  //
  //
  file_put_contents(__DIR__ . "/application.json", json_encode($application, JSON_PRETTY_PRINT));


  $message = "The member is now in the list of attendees"; 
  route(sprintf("./?message=%s",$message));
} else {
  die("Please set the attendee in request");
}
?>
