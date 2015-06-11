<!DOCCTYPE HTML>
<html>
  <head>
  <?php require_once("../bootstrap.php"); ?>
  </head>
  <body>
  <?php generateMenu(); ?>


  <?php if (!isConnected()): ?>
    <div class="alert">
      You have not updated credentials.json with your Catapult credentials, please do so to use the examples.
    </div>
  <? endif; ?>
  <div class="app-content">
    <h2>Catapult PHP Examples</h2>

     <p>
     Menu on the left includes examples for running the Catapult PHP SDK in 
     production. 
     
     You should set up the application's 'application.json' file before using, 
     make sure to use your Catapult numbers! 
     </p>


    <h2>Bandwidth API Credentials</h2>
    <p>
     Your Bandwidth credentials are located in {root}/main.json, you will need to update this
    </p>
    <h2>Running on AWS Elastic Beanstalk</h2>
     Heres's how to deploy this to AWS 

      <pre>
  eb start
  git aws.push</pre>
    <b>Note:</b> These commands should be ran in the root of catapult-php-examples 
    <br />
    <h2>Running on Heroku</h2>
     Here's how to deploy this on Heroku
      
     <p>
        <pre>
  heroku create "php-bandwidth-examples"
  git push heroku master</pre>
      
     </p>



    <h2>Notes and Prequisites</h2>

        <img src="../img/php-logo.png"/>
      <ul class="normal-list">
        <li>- These examples use Catapult >= 0.7.0 and PHP >= 5.3. 
        You can always find the latest Catapult version at: 
        <a href="https://github.com/bandwidthcom/php-bandwidth">GitHub</a>

        <br />
        <br />
        <b>Note:</b> Should your PHP version be below 5.3, you will need to upgrade
        </li>

      </ul>
      <br />
  </div>
  </body>
</html>
