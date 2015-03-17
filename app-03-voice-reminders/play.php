<?php
$UI=FALSE;
require_once("../config.php");
require_once("./config.php");
// For demo purposes only:
//
// This file fetches a recording and 
// makes its media available to the requestor
// we will use Catapult's Recording object
// with Media


if (isset($_REQUEST['recordingId']) && $_REQUEST['recordingId'] !== "") {

  $client = new Catapult\Client;
  $recording = new Catapult\Recording($_REQUEST['recordingId']);
  // get the media file and set 
  // the content-type to wav
  $media = $recording->getMediaFile();

  header("Content-Type: audio/wav");

  // media->data in media 
  // is the the raw contents
  // of our media which is exceptional
  // here as we serve on request
  echo $media->data;
} else {

  die("This recording is not ready or was corrupted. Sorry");
}
