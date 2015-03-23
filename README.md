Example Applications For Bandwidth PHP SDK
======================================================

Includes:
-----------------------------------------------------
* coming soon

  - SMS Auto Replies
  - Call Transfers 
  - Voice Reminders
  - Basic Voice Mail
  - Basic Conference
  - Advanced Conference
  - Keypad Simulator
  - BaML Call Transfers
  - SIP Domain & Endpoints *


Setup
------------------------------------------------------

1. Starters file credentials.json stores your
Bandwidth Credentials, you should edit this first


2. Each application has a application.json which includes
information relative to the application. To run the app, please
update this. You will also need to ensure numbers are
in E.164 format and the Voices are valid for Catapult v1

3. Under your Catapult Account we've used a seperate Application
for each application listed. You can do so as well by logging
into your Catapult Account and making the applications. You will
need to ensure:

  1. Call URLs are setup properly 
     this is the Callback URL please   
     point this to the callback.php (each application has one)
  
  2. Make sure the numbers used in the application.json
     belong to the same application. 

4. To start testing please make sure you have
ran an application once it will create all the table
for these demos



Deploying
=========================================================

Once you've setup your applications you can deploy. Currently
tested with Heroku and Amazon Elastic Beanstalk.


Heroku
-------------------------------------------------------
you will need to create an application you can then deploy 


  heroku create "php-bandwidth-examples"
  heroku config:set ON_HEROKU=1
  git push heroku master


To run these examples on heroku you will need clearDB OR postgres, which can
be installed using:

  heroku addons:add heroku-postgresql:dev 
  OR 
  heroku addons:add cleardb:ignite 


currently Heroku is tested with postGresql




AWS
-----------------------------------------------------------

  eb start
  git aws.push


for AWS you will need to set:

  MYSQL_USER
  MYSQL_PASSWORD
  MYSQL_HOST
  MYSQL_DB




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

App 008 BaML Call Transfers
--------------------------------------------------------

This will generate verbs in Bandwidth Markup Language
the verbs will then be used to update your calls. In the log
you will be able to see all the markup generated

Setting up:
We have listed
  1. Enter a valid initial number and to number
  2. Initiate a call

App 009  SIP Domains 
-------------------------------------------------------

This application creates domains and endpoints using
Catapult. It will provide an interface to do so. Through it
you will be able to see step approach of creating
these domains as well as integrating them with the endpoints.

As like the other interfaces you can also see the history of SIP calls
made.

Setting Up:
  1. Specify valid names to the interface 
  2. Use these domains in creating your endpoints 


Docs
---------------------------------------------------------

These applications are documented in the following way:
  * Steps (these are things that are absolutely needed for the application to run)
  * Important (things that we need)
  * Recommendation (these are branches that are optional however highly recommended)
  * Optional (implementors choice)

Other:

an Implementors Note will describe the segment 
and whether there are things they should be concerned with

a Tip is a hint on which objects to use

Validation will describe how to validate
using the Catapult library


Application DB Syntax
-----------------------------------------------------------
TO make sure these examples run on your RBDMS we have used
a simple style guide:

- underscores in all our table names
- no infixes for the table names
- reserved characters where applicable
  i.e
  "from" for postGresQL
   will be
  `from` for SQLite
- application variables are camelCased and so are JSON objects



Debugging
===========================================================

debugging in Heroku, if you know how to already should
be trivial, one command used in our tests was:

   heroku logs -t

Which will stream callback information as it happens, usually
logs being kept in the 'logs' directory relative to the application
is also helpful and you may want to check this. If you have
questions or concerns please email the maintainer of this package.


debugging local
debugging these applications locally while different from Heroku,
can still be done using the logs, and PHP warnings. 


Other Notes
===========================================================

- Depending on how the callback is implemented some application
records may take time to appear in the interface.


These examples require:

Catapult PHP SDK >= 0.7.3
SQLite3
PHP 5.3.0
Apache HTTPd >= 2.2 OR nGinx 


Deploying Anywhere:
  SQLite3


When using Heroku:
  PostGresQL 
  ClearDB with MySQL

AWS:
  MySQL
