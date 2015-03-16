<!DOCCTYPE HTML>
<html>
  <head>
  <?php require_once(__DIR__."/../bootstrap.php"); ?>
  </head>
  <body>
    <div style="border: 1px solid #E3E3E3; padding: 20px; ">
    <h2>Error - API Credentials</h2>
      <hr />
      <small><b>You must include your Catapult API keys</b></small>
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

    </div>


  </body>
</html>
