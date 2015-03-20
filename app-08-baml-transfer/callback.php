<?php
// include both configs
require_once(__DIR__."/../config.php");
require_once(__DIR__."/config.php");


// BaML Call Transfers 
//
// For more on BaML read:
//
// http://ap.bandwidth.com/docs/xml/
//
// This will transfer calls like application 002
// only using BaML Markup in doing so. Through the
// application there are BaML verbs we use to  
// accomplish this, these verbs and there definitions
// found above are all available as part of the PHP SDK

// Important:
//
// to run this callback make sure your application's
// Callback Method is set to GET. All BaML responses
// require this
//

try {
  // Step 1.
  //
  // There is one generic object
  // that will hold all your BaML verbs
  // it can be either a Request or Response
  // in this example we are using a callback,
  // as a result will use Response
  //
  // 

  // Important
  //
  // for BaML our Content-Type
  // must be set to application/xml
  // we can do this using header/1
  header("Content-Type: application/xml");

  $bamlContainer = new Catapult\BaML('Response');

  // BaML verbs are all accessible
  // through the BaML interface
  // here we will be using SpeakSentence 
  // Transfer and Hangup
  $transferCall = new Catapult\BaMLTransfer;
  $speakSentence = new Catapult\BaMLSpeakSentence;
  $hangupCall = new Catapult\BaMLHangup;

  // set the voice and
  // gender a full list of  
  // accepted voices found on:
  //
  // http://ap.bandwidth.com/docs/xml/speaksentence/
  //
  $speakSentence->addAttribute("voice", $application->BaMLTransferVoice);
  $speakSentence->addAttribute("gender", $application->BaMLTransferVoiceGender);
  $speakSentence->addAttribute("locale", $application->BaMLTransferVoiceLocale);

  // our inner text will produce the
  // speech this will be with the above settings 
  $speakSentence->addText($application->BaMLTransferSentence);

  // Step 2.
  //
  // Verbs can be added in parallel
  // so we will make transferCall our second.
  // Settings to transferCall being very much
  // like their model counterparts
  $transferCall->addAttribute("transferTo", $application->BaMLTransferToNumber);
  // Recommended
  //
  // setting our transferCallerId while not mandatory
  // can be useful. Here it will
  // be the number we used in listening
  $transferCall->addAttribute("transferCallerId", $application->BaMLTransferListeningNumber);

  // another recommendation
  // is to hangup calls once we're done
  // this ensures we have completed our 
  // transfer and are no longer using 
  // this call. Hangup call requires no instruction
  // $hangupCall


  // Step 3.
  //
  // at the root of a BaML container you
  // can add verbs, multiple if needed.
  // here we will add the verbs we've
  // generated.
  $bamlContainer->add($speakSentence);
  $bamlContainer->add($transferCall);
  $bamlContainer->add($hangupCall);

  // finally output the contents 
  //
  // all bamlContainers can 
  // be outputted in markup.
  // on success this should
  // return a BaML document
  // which can be viewed as a XML file
  printf("%s",$bamlContainer);





  // Optional
  //
  // store the information
  // in our database
  // also serialize the xml
  $date = new DateTime;
  addRecordBasic($application->applicationTable, 
  array($application->BaMLTransferToNumber, 
  $application->BaMLTransferListeningNumber, serialize((string)$bamlContainer), $date->format("Y-m-d")));

} catch (CatapultApiException $e) {

// Recommended
//
// BaML verbs are highly unlikely to throw warnings
// however there can be lower level warnings like input errors
// or checks we may run into so we will
// add an exception here.

}

