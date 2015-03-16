Application 003 -- Voice Reminders
=========================================================

To setup voice reminders make sure:
  1. You have valid numbers and a valid catapult voice 
    valid voices:
      English US: Kate, Susan, Julie, Dave, Paul
      English UK: Bridget
      Spanish: Esperanza, Violeta, Jorge
      French: Jolie, Bernard
      German: Katrin, Stefan
      Italian: Paola, Luca 
   for an updated list visit:
   https://catapult.inetwork.com/docs/api-docs/calls/

  2. You need to also setup the callback url to your servers

  4. Please make sure the voicemailNumber is specified

  3. Voice reminders will be stored in ./data/ and on success be available
  throw the application's interface

Note:

While this application performs a voice reminder sequence it WILL not
remind the users on the date as it would take an external program in doing
so -- this is something the implementor must do. We have written a demo
for this which is available under 'external.php'

More:
Once your reminders have been set they will be viewable through the
application's interface which will display the time/date and recording
