Application 008 -- BaML Transfers
=========================================================

To set up BaML transfers
 1. Please make sure the listening number points to a valid number
 on your catapult account

 2. Setup the callbackUrl in applications.json this will need to be 
 the full address.

 3. Important: BaML callbacks require a GET method in your application,
 make sure your application's Callback HTTP method is set accordingly

Notes:
Unlike other examples this will demonstrate the usage of BaML markup
without any events so the callback.php will need to be set to:

Content-Type: application/xml

this is for all BaML callbacks

This application requires an application id which will be used to validate
your application
