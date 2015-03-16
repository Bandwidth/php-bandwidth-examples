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
    "table" => "sms_auto_replies",
    "link" => "app-01-auto-replies"
  ),
  array(
    "name" => "Incoming Call Transfers",
    "table" => "incoming_call_transfers",
    "link" => "app-02-transfer-call"
  ),
  array(
    "name" => "Voice Reminders",
    "table" => "voice_reminders",
    "link" => "app-03-voice-reminders"
  ),
  array(
    "name" => "Basic Voice Mail",
    "table" => "basic_voice_mail",
    "link" => "app-04-basic-voicemail"
  ),
  array(
    "name" => "Basic Conference",
    "table" => "basic_conference",
    "link" => "app-05-basic-conference"
  ),
  array(
    "name" => "Advanced Conference",
    "table" => "advanced_conference",
    "link" => "app-06-advanced-conference"
  ),
  array(
    "name" => "Keypad Simulator",
    "table" => "keypad_simulator",
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
    "table" => "advanced_conference_data",
    "schema" => "CREATE TABLE advanced_conference_data (
        callFrom VARCHAR(255),
        receiverCallFrom VARCHAR(255),
        code VARCHAR(255),
        attended VARCHAR(255),
        conferenceId VARCHAR(255),
        digits VARCHAR(255)
    ); ",
    "type" => "data"
  ),
  array(

    "table" => "voice_reminders_data",
    "schema" => "CREATE TABLE voice_reminders_data (
      callId VARCHAR(255),
      recordingId VARCHAR(255), 
      title VARCHAR(255),
      speech VARCHAR(255),
      initiated VARCHAR(255),
      message VARCHAR(255),
      month VARCHAR(255), 
      thanks VARCHAR(255),
      year VARCHAR(255),
      date VARCHAR(255)
    ); ",
    "type" => "data"

  ),
  array(
    "table" => "basic_voice_mail_data",
    "schema" => "CREATE TABLE basic_voice_mail_data (
       callId VARCHAR(255),
       recordingId VARCHAR(255),
       mediaName VARCHAR(255),
       initiated VARCHAR(255),
       date VARCHAR(255)
     ); " 
  ),
  array(
    "table" => "basic_conference_data",
    "schema" => "CREATE TABLE basic_conference_data (
        callFrom VARCHAR(255),
        conferenceId VARCHAR(255),
        callId VARCHAR(255)
    ); ",
    "type" => "data"
  ),
   array(
    "table" => "keypad_simulator_data",
    "schema" => "CREATE TABLE keypad_simulator_data (
        level VARCHAR(255),
        callId VARCHAR(255),
        key VARCHAR(255)
    ); ",
    "type" => "data"
  )
);


?>
