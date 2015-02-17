Example Applications For Bandwidth PHP SDK
======================================================

Includes:
-----------------------------------------------------
  - SMS Auto Replies
  - Call Transfers 
  - Voice Reminders
  - Basic Voice Mail
  - Basic Conference
  - Advanced Conference
  - Keypad Simulator


Setup
------------------------------------------------------

1. Starters file credentials.json stores your
Bandwidth Credentials, you should edit this first


2. Each application has a application.json which includes
information relative to the application. To run the app, please
update this. You will also need to ensure numbers are
in E.164 format and the Voices are valid for Catapult v1

Deploying
=========================================================

Once you've setup your stuff you can deploy. Currently
tested with Heroku and Amazon Elastic Beanstalk.


Heroku
-------------------------------------------------------
you will need to create an application you can then deploy 

  heroku create "php-bandwidth-examples"
  git push heroku master


AWS
-----------------------------------------------------------

  eb start
  git aws.push



App 001 SMS Auto Replies
----------------------------------------------------------

Requirements:
A Number under your Catapult account

Setting up:
  1. use callback.php as the callback to your application
  under https://catapult.inetwork.com/pages/catapult.jsf
  2. Enter a valid number from your catapult account 

Other:
Make sure the number is also under this application


App 002 Call Transfers
-----------------------------------------------------------

Requirements:
A listening and transfering number for your Catapult account

Setting up:
  1. use callback.php as the callback to your application
  under https://catapult.inetwork.com/pages/catapult.jsf
  2. Enter two valid numbers from your catapult account in
  application.json


App 003 Voice Reminders
-----------------------------------------------------------

Requirements:
A number to use for voice reminders

Setting up:
  1. use callback.php as the callback to your application
  under https://catapult.inetwork.com/pages/catapult.jsf
  2. Enter a valid number for your voice reminders

App 004 Basic Voice Mail
-----------------------------------------------------------

Requirements:
A number to use for voice reminders

Setting up:
  1. use callback.php as the callback to your application
  under https://catapult.inetwork.com/pages/catapult.jsf
  2. Enter a valid number for your voice mail 


App 005 Basic Conference
---------------------------------------------------------

Requirements:
A number to use for the conference

Setting up:
  1. use callback.php as the callback to your application
  under https://catapult.inetwork.com/pages/catapult.jsf
  2. Enter valid attendees and initial from number
  in application.json


App 006 Advanced Conference
---------------------------------------------------------

Requirements:
A number to use for the conference

Setting up:
  Setup is similar to basic conference


App 007 Keypad Simulator
--------------------------------------------------------

Keypad simulator is a quick way to simulate a fully capable keypad service it will provide an easy way to capture DTMF keys
sequentially save them using SQLite and even perform transfers.

Setting up:
   1. Enter valid numerical sequences for your keypad
   2. Needs a valid incoming number 
   3. Existing start and intermediatte speech texts


Other Notes
===========================================================

These examples need:

  Catapult PHP SDK 0.7.0
  SQLite3
  PHP 5.3.0

