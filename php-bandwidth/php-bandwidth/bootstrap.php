<?php
/**
 * Includes all the CSS/JS used for these applications
 * feel free to add or change.
 *
 * files includes in 
 * {top_level}/js
 * {top_level}/css
 *
 *
 * This file is included throughout the examples.
 */

$files = array(
  array(
    "type" => "css",
    "file" => "bootstrap.min"
  ),
  array(
    "type" => "css",
    "file" => "main"
  ),
  array(
    "type" => "js",
    "file" => "bootstrap.min"
  )
);
$applications = array(
  array(
    "name" => "SMS Auto Replies",
    "link" => "app-01-auto-replies"
  ),
  array(
    "name" => "Incoming Call Transfers",
    "link" => "app-02-transfer-call"
  ),
  array(
    "name" => "Voice Reminders",
    "link" => "app-03-voice-reminders"
  ),
  array(
    "name" => "Basic Voice Mail",
    "link" => "app-04-basic-voicemail"
  )

);

// include the sqLite database
require_once(__DIR__ . "/db.php");
// include menu generation functions
require_once(__DIR__ . '/menu.php');

foreach ($files as $file) 
  if ($file['type']=='js')
    printf("<script src='%s' type='text/javascript'></script>\n", "../".$file['type']."/".$file['file'].".".$file['type']);
  else
    printf("<link rel='stylesheet' href='%s' />\n", "../".$file['type']."/".$file['file'].".".$file['type']);
?>
