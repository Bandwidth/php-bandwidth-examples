<?php

/**
 * Our tables for the application
 * each should have the basic table
 * additionally some can have the
 * data table
 */
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
  ),
/*
  array(
    "name" => "BaML Transfer Verb",
    "link" => "app-08-baml-transfer"
  ),
  array(
    "name" => "SIP Domains Application",
    "link" => "app-09-sip-domains"
  )
*/
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
        `attended` VARCHAR(255),
        `conferenceId` VARCHAR(255),
        `digits` VARCHAR(255)
    ); ",
    "type" => "data"
  ),
  array(

    "name" => "Voice Reminders Data",
    "schema" => "CREATE TABLE `Voice Reminders Data` (
      `callId` VARCHAR(255),
      `recordingId` VARCHAR(255), 
      `title` VARCHAR(255),
      `speech` VARCHAR(255),
      `initiated` VARCHAR(255),
      `message` VARCHAR(255),
      `month` VARCHAR(255), 
      `thanks` VARCHAR(255),
      `year` VARCHAR(255),
      `date` VARCHAR(255)
    ); ",
    "type" => "data"

  ),
  array(
    "name" => "Basic Voice Mail Data",
    "schema" => "CREATE TABLE `Basic Voice Mail Data` (
       `callId` VARCHAR(255),
       `recordingId` VARCHAR(255),
       `mediaName` VARCHAR(255),
       `initiated` VARCHAR(255),
       `date` VARCHAR(255)
     ); " 
  ),
  array(
    "name" => "Basic Conference Data",
    "schema" => "CREATE TABLE `Basic Conference Data` (
        `callFrom` VARCHAR(255),
        `conferenceId` VARCHAR(255),
        `callId` VARCHAR(255)
    ); ",
    "type" => "data"
  ),
   array(
    "name" => "Keypad Simulator Data",
    "schema" => "CREATE TABLE `Keypad Simulator Data` (
        `level` VARCHAR(255),
        `callId` VARCHAR(255),
        `key` VARCHAR(255)
    ); ",
    "type" => "data"
  ),
  array(
    "name" => "BaML Call Transfers Data",
    "schema" => "CREATE TABLE `BaML Call Transfers Data` (
      `verb` VARCHAR(255),
      `markup` VARCHAR(255)
    ); ",
    "type" => "data"
  ),
  array(
    "name" => "SIP Domains Data",
    "schema" => "CREATE TABLE `SIP Domains Data` (
      `domain` VARCHAR(255),
      `from` VARCHAR(255),
      `to` VARCHAR(255)
    ); ",
    "type" => "data"
  )
);


?>
