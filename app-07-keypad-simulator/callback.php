<?php

require_once("../config.php");
require_once("./config.php");

$client = new Catapult\Client;

// Keypad simulator events should
// all be dtmf events followed by playbacks
// 
// This will function according to what
// is in application.json. 
//

// Our main event object
$event = new Catapult\Event;


if ($event->eventType == "incoming") {
  $call = new Catapult\Call($event->id);
  $call->accept();

  // play our main speech
  $context = $application->keypadSimulatorNumbers;
  $call->speakSentence(array(
    "sentence" => $context->speech
  ));


  // log it in the database
  // we will later add to this
  // with each gather collected
  $date = new DateTime;
  addRecord($application->table,array($application->keypadSimulatorNumber, $messageEvent->from, "", $date->format("Y-M-D H:i:s")));

} else if ($event->eventType == "hangup") {
  // handle user hangups
  // this means cleaning the
  // database
  //
  $db->query(sprintf("DELETE FROM `%s` WHERE callFrom = '%s'", $application->datatable, $event->from));


} else if ($event->eventType == "dtmf") {

  /**
   * figure out where we are in the keypad 
   * simulator stage this is done by level
   * and the last key pressed. once we reach 
   * an endpoint we can stop. 
   * 
   * other things to consider:
   * - when no context is present use our main speech
   * - when a forward is present use our transfer feature
   * - we need to gracefully close our connection if we are finished
   */

    $call = new Catapult\Call($event->id);
    $dtmf = new Catapult\Dtmf($event->dtmfDigit);
    $transferred = FALSE;
    $context = $application->keypadSimulatorNumbers;


   // as a priminitive we need to
   // check if the exit key was pressed
   if ($dtmf == $application->keypadSimulatorExitKey) {

      // the exit key was pressed.
      // we should close the call
      // and say our goodbye speech
      $call->speakSentence(array(
        "sentence" => $application->keypadSimulatorExitSpeed, 
        "voice" => $application->keypadSimulatorVoice
      ));


      $call->hangup();

      // finalize and clean our data
      $db->query(sprintf("DELETE FROM `%s` WHERE callFrom = '%s'",  $application->datatable, $call->from));


   }


   // standard execution:
   // need to know where we are in the simulator
   // get the count of levels
   // the user has traversed
   $level = getCount(sprintf("SELECT COUNT(*) as count FROM `Keypad Simulator Data` WHERE callFrom = '%s'",$call->from));

   // add this key in our records only
   // if its a valid one for context
   // we will use this in our next request
   // if possible

     $context = $application->keypadSimulatorNumbers;

     if ($level > 0) { 

       // quick history
       // for what has been done
       // in this keypad simulation
       $collection = array(); 
       for ($i = 0; $i != $level; $i ++) {
          $ltext = $db->query(sprintf("SELECT * FROM `Keypad Simulator Data` WHERE callFrom = '%s' AND level = '%s'",$call->from, $i));
          while ($rec = $ltext->fetchArray()) {
            $key = $rec['key'];
            $context = $context->$key;


            /** keep an enumerated list of digit occurences **/
            $collection[] = $context;
          }
       }
     }


      // now lets check if our current digit is a valid digit
      // we do this by by listing all the properties
      // for our Application object (JSON object).
      // the keys should be numerical so should this 
      // dtmf key
    
      if (array_key_exists($dtmf, get_object_vars($context)) && is_numeric($dtmf)) {

        // we can add this
        // to our database it was
        // a valid key

        addRecord($application->datatable, array($call->from, $level, $dtmf), array("callFrom", "level", "key")); 
      } else {

        // wrong.
        // this dtmf key was not valid
        // for the schema. Warn the user and await further input

        $call->speakSentence(array(
         "voice" => $application->keypadSimulatorVoice,
          "sentence" => $application->keypadSimulatorWrongDigit
        ));
         
      }
     
 
    
     // Second section  
     // Handle speech. And transfer calls where possible  
     // Switch the current context on at this point
     // it is a valid keypress and we should handle it
     
     $current = $context->$dtmf;

     // check if we need
     // to transfer
     if ($current->transfer) {

        // speak a sentence
        // then transfer the call
        // call
        //
        // this should cleanup as if the
        // was finished

        $call->speakSentence(array(
          "voice" => $application->keypadSimulatorVoice,
          "sentence" => $current->speech
        ));
        $call->transfer($current->transferNumber);

        $transferred = TRUE;

     } else { 

       // speak the sentence
       // for this keypess
       $call->speakSentence(array(
        "voice" => $application->keypadSimulatorVoice,
        "sentence" => $current->speech
       ));

     }
      
   // here we check
   // if we can continue

   // we do this by checking if our
   // first occurence if avaialable. This only
   // works because we are using sorted numbers
   // if you plan on using a difference order scheme
   // make sure this is changed

   // also check if we have transferred as we can
   // no longer perform when done
   if (!array_key_exists("1", get_object_vars($current)) 
      || $transferred) {

      $call->hangup();

      // take out all intermediatte data for this call
      $db->query(sprintf("DELETE FROM `%s` WHERE callFrom = '%s'",  $application->datatable, $call->from));
   }
}

?>
