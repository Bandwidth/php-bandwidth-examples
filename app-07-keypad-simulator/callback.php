<?php

require_once(__DIR__."/../config.php");
require_once(__DIR__."/config.php");

// Keypad Simulator
//
// this application demonstrates a simple
// way to run a sequential keypad service
// it will track each dtmf code and traverse
// where we are througtout a call
//
// Here's how it works:
//
// 1. Keep a number to dial in (this will be the service number)
// 2. Listen to dtmf events, store each
// 3. Given the dtmf events figure out where we are and what to do
// using application.json

// Implementors Note:
//
// Keypad simulator events should
// all be dtmf events followed by playbacks.
// 
// This will function according to what
// is in application.json. 

$client = new Catapult\Client;
// For the keypad simulator
// we are usinhg a manual approach
// thus use IncomingCallEvent
// if you plan on using auto
// comment this out
$inboundCallEvent = new Catapult\IncomingCallEvent;
// uncomment out if using auto
//$inboundCallEvent = new Catapult\AnswerCallEvent;
$hangupCallEvent = new Catapult\HangupCallEvent;
$dtmfCallEvent = new Catapult\DtmfCallEvent;
$errorCallEvent = new Catapult\ErrorCallEvent;
$timeoutCallEvent = new Catapult\TimeoutCallEvent;

if ($inboundCallEvent->isActive()) {

  // Step 1.
  //
  // Listen to incoming calls on the number
  // and accept it.
  

  $call = new Catapult\Call($inboundCallEvent->id);


  // Important
  //
  // when using the manual approach
  // we will need to accept calls
  if ($call->state == Catapult\CALL_STATES::started) {
    $call->accept();
  }

  // play our main speech
  $context = $application->keypadSimulatorNumbers;
  $call->speakSentence(array(
    "sentence" => $context->speech,
    "voice" => $application->keypadSimulatorVoice,
    "gender" => $application->keypadSimulatorVoiceGender
  ));

  // Optional
  //
  // save the initial data in SQlite

  $date = new DateTime;
  addRecordBasic($application->applicationTable, array($call->from, $call->to, $call->id, $date->format("Y-m-d")));
} 

if ($hangupCallEvent->isActive()) {
  // Recommended
  //
  // handle hangups this could be during post or inter call 
  // we should probably clean up our intermiediatte data here
  // 
  // for this demo, given it is on display we won't
  //$db->query(sprintf("DELETE FROM `%s` WHERE callId = '%s'", $application->applicationDataTable, $hangupCallEvent->from));
}

if ($dtmfCallEvent->isActive()) {

  // Step 2
  //
  // figure out where we are in the keypad 
  // simulator stage this is done by level
  //  and the last key pressed. once we reach 
  // an endpoint we can stop. 
  // 
  // other things to consider:
  // - when no context is present use our main speech
  // - when a forward is present use our transfer feature
  // - we need to gracefully close our connection if we are finished



  // First section
  // 
  // we are given dtmf and with this we need
  // to set up the priminitive data.
  //
    $call = new Catapult\Call($dtmfCallEvent->callId);

    $dtmf = $dtmfCallEvent->dtmfDigit;
    $context = $application->keypadSimulatorNumbers;


   // Recommended
   //
   // since we have received input
   // we need to stop the current sentence

   $call->stopSentence();



   // Optional    
   //
   // having an exit digit allows
   // for a better integration this however
   // is not needed
   if ($dtmf == $application->keypadSimulatorExitDigit) {
  
      // the exit key was pressed.
      // we should close the call
      // and say our goodbye speech
      $call->speakSentence(array(
        "sentence" => $application->keypadSimulatorExitSpeech, 
        "voice" => $application->keypadSimulatorVoice,
        "gender" => $application->keypadSimulatorVoiceGender
      ));


      sleep(7);
      $call->hangup();

      // finalize and clean our data
      $db->query(sprintf("DELETE FROM `%s` WHERE callId = '%s'",  $application->applicationDataTable, $call->id));


   }


   // standard execution:
   // need to know where we are in the simulator
   // get the count of levels
   // the user has traversed
   $level = getCount(sprintf("SELECT COUNT(*) as count FROM `Keypad Simulator Data` WHERE callId = '%s'",$call->id));

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
       for ($i = 0; $i <= $level; $i ++) {
          $ltext = $db->query(sprintf("SELECT * FROM `Keypad Simulator Data` WHERE callId = '%s' AND level = '%s'",$call->id, $i));
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
      //
      // Optional: 
      // checking if a key is numeric is a good rule
      // because our application only accepts numeric
    
      if (array_key_exists((int)$dtmf, get_object_vars($context))) { 
        // Important
        //
        // we can add this to our database it was
        // a valid key.

        addRecord($application->applicationDataTable, array($call->id, $level, $dtmf), array("callId", "level", "key")); 
      } else {

         // Recommended
         //
         // we need to check two things if we can
         // continue and if the dtmf is invalid

         // we do this by checking if our
         // first occurence if available. This only
         // works because we are using sorted numbers
         // if you plan on using a difference order scheme
         // make sure this is changed

         if (!array_key_exists("1", get_object_vars($context))) {

            // this means the user has reached
            // an ending we should
            // hangup the call and remove his
            // data
            $call->hangup();

            // take out all intermediatte data for this call
            //
            // for the demo we will keep this available

            $db->query(sprintf("DELETE FROM `%s` WHERE callId = '%s'",  $application->applicationDataTable, $call->id));

            exit(1);
         } else {


          // We can continue
          // however the Dtmf is invalid
          // so we need to alert the user


          $call->speakSentence(array(
           "voice" => $application->keypadSimulatorVoice,
            "gender" => $application->keypadSimulatorVoiceGender,
            "sentence" => $application->keypadSimulatorWrongDigit
          ));

           exit(1);

         }
         
      }


     // Before we go into the next section
     // we can now set our current 
     // which will be the dtmf entered for
     // this stage
     $current = $context->$dtmf;
   

     // Second section  
     //
     // Handle speech. And transfer calls where possible  
     // Switch the current context on at this point
     // it is a valid keypress and we should handle it

     // check if we need
     // to transfer
    


     if (isset($current->transfer) && $current->transfer) {

        // Important
        //
        // speak a sentence then transfer the call
        // call.
        //
        // this should cleanup as if the
        // call was finished

        $call->speakSentence(array(
          "voice" => $application->keypadSimulatorVoice,
          "gender" => $application->keypadSimulatorVoiceGender,
          "sentence" => $current->speech
        ));
        sleep(5);
        $call->transfer($current->transferNumber);

     } 

     if (isset($current->balance) && $current->balance) {
      // Optional
      // 
      // here we treat 
      // balances as a special
      // segment we will add display the balance when specified
      //
      // Implementors Note:
      // 
      // account based information is available in the
      // accounts object
      $account = new Catapult\Account;
      $account->get();
      $text = $current->speech . $account->balance;
      
      $call->speakSentence(array(
        "sentence" => $text,
        "voice" => $application->keypadSimulatorVoice,
        "gender" => $application->keypadSimulatorVoiceGender
      ));
      }

     if (!isset($current->balance) && !isset($current->transfer)) { 

       // Important
       // 
       // speak the sentence for the current
       // phase in execution. 

       $call->speakSentence(array(
        "voice" => $application->keypadSimulatorVoice,
        "gender" => $application->keypadSimulatorVoiceGender,
        "sentence" => $current->speech
       ));
     }
}

if ($errorCallEvent->isActive()) {

  // Recommended
  //
  // handle errors for this application.
  //
  // Implementors should likely
  // log this as it will provide the information on 
  // the call
}

if ($timeoutCallEvent->isActive()) {

  // Important
  //
  // handle timeouts for this applications
  // for this application we merely tell the user
  // to try later. It is important to

  $call = new Catapult\Call($timeoutCallEvent->id);
  $voice = new Catapult\Voice($application->keypadSimulatorVoice);


  $call->speakSentence(array(
    "voice" => $voice,
    "gender" => $application->keypadSimulatorVoiceGender,
    "sentence" => $application->keypadSimulatorTimeout 
  ));

  sleep(5);
  $call->hangup();

}

?>
