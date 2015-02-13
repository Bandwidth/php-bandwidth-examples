<?php
require_once("../php-bandwidth/source/Catapult.php");
require_once("../config.php");
require_once(__DIR__ . "/config.php");
/**
 * Voice Reminders with Catapult. This
 * will perform the flow as described here:
 * https://catapult.inetwork.com/docs/guides/voicemail/
 *
 * Before anything you should set up your application.json
 * Needs:
 * voiceReminderText -- this will be used in the call
 * voiceReminderNumber -- this will be used as a number
 * voiceReminderVoice -- this will be the voice used to render speech
 */
// IMPORTANT: 
// This file is the base wrapper behind
// the interface. Implementors should look in
// callback.php which is the application's 
// implementation. This provides rules for
// checking whether your application has been
// setup properly.



$client = new Catapult\Client;
$reminders = $db->query("SELECT * FROM `" . $application->applicationName . "`; ");
$remindersCnt = count($reminders) - 1; // SQLite returns + 1 
/**
 * Important:
 * when using apps: 01 and 02 you may have noticed we
 * used the catapult listing feature for this
 * given we cannot keep track of all reminders with this
 * feature we're using sqLite to save each reminder
 *
 */

$recording = new Catapult\Recording;

$pn = new Catapult\PhoneNumber($application->voiceReminderNumber);
if (!$pn->isValid()) {
  $message = 'Voice Reminder number is not in E.164 format';
}

$PhoneNumbers = new Catapult\PhoneNumbersCollection;
$PhoneNumbers->listAll();
$PhoneNumbers->find(array("number" => $application->voiceReminderNumber));

if ($PhoneNumbers->isEmpty()) {
  $message = "This number is not listed under your Catapult account";
}

$Voice = new Catapult\Voice($application->voiceReminderVoice);

if (!$Voice->isValid()) {
  $message = "$Voice is not a valid voice for Catapult";
}

if (!isset($message)) {
  $message = $application->voiceReminderNumber . " is listening for voice reminders, go a head and dial";
  $status = "success";
}
?>
