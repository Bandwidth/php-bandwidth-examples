<!DOCCTYPE HTML>
<html>
  <head>
  <?php require_once(__DIR__."/../bootstrap.php"); ?>
  <?php
  // when we're already
  // connected do not
  // come back here!
  if (isConnected()) {
    route("./");
  }
  ?>
  </head>
  <body>
    <div style="border: 1px solid #E3E3E3; padding: 20px; ">
    <h2>Error - API Credentials</h2>
      <hr />
      <small><b>You must include your Catapult API keys or your current credentials are invalid</b></small>
      <br />
      <br />

      <li>Edit credentials.json in the root directory</li>
      <small>The file should resemble: </small>
      <pre>

       {
          "BANDWIDTH_USER_ID": "__USER_ID__",
          "BANDWIDTH_API_TOKEN": "__API_TOKEN__",
          "BANDWIDTH_API_SECRET": "__API_SECRET__",
       }  

      </pre>
      <small>
        You can find your account information on:<br />
        <a href ="https://catapult.inetwork.com/pages/login.jsf">https://catapult.inetwork.com/pages/login.jsf</a>
      </small>
      <br /> 
      <br />
      <br />
      <hr />
      <small>Have you updated these keys? </small>
      <a class="btn-primary" href="./">Go back</a>

    </div>


  </body>
</html>
