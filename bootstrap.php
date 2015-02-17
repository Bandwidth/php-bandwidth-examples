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
  ),
  array(
    "name" => "Basic Conference",
    "link" => "app-05-basic-conference"
  ),
  array(
    "name" => "Advanced Conference",
    "link" => "app-06-advanced-conference"
  ),
  array(
    "name" => "Keypad Simulator",
    "link" => "app-07-keypad-simulator"
  )


);

/**
 * Data tables these will carry
 * different schema and will be used
 * for internal storage
 */
$tables = array(
  array(
    "name" => "Advanced Conference Data",
    "schema" => "CREATE TABLE `Advanced Conference Data` (
        `callFrom` VARCHAR(255),
        `receiverCallFrom` VARCHAR(255),
        `code` VARCHAR(255),
        `fullName` VARCHAR(255),
        `conferenceId` VARCHAR(255),
        `digits` VARCHAR(255)
    ); ",
    "type" => "data"
  ),
  array(
    "name" => "Basic Conference Data",
    "schema" => "CREATE TABLE `Basic Conference Data` (
        `callFrom` VARCHAR(255),
        `conferenceId` VARCHAR(255)
    ); ",
    "type" => "data"
  ),

  array(
    "name" => "Keypad Simulator Data",
    "schema" => "CREATE TABLE `Keypad Simulator Data` (
        `level` VARCHAR(255),
        `callFrom` VARCHAR(255),
        `key` VARCHAR(255)
    ); ",
    "type" => "data"
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
