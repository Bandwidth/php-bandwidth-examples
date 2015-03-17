Application 004 -- Voice Mail 
=========================================================

To setup voice mail make sure:
  1. You have valid numbers and valid beep tone this is by default 
  stored in ./files/

   for an updated list visit:
   https://catapult.inetwork.com/docs/api-docs/calls/

  2. You need to also setup the callback url to your servers

  3. Please make sure you specify your voicemail number

  4. Directory /data/ will need to r+w on your server

Note:

This application will receive the media from Catapult's server
and make and rename it to something unique and identifiable by a
human. As our last application (003) we are still using the recording
event.

