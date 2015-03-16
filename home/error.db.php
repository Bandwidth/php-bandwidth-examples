<!DOCCTYPE HTML>
<html>
  <head>
  <?php require_once("../bootstrap.php"); ?>
  </head>
  <body>
    <div style="border: 1px solid #E3E3E3; padding: 20px; ">
    <h2>Error - Database connection<h2>
      <hr />
      <small><b>In order to run php-bandwidth-examples you must do one of the following:</b></small>
      <br />
      <br />

      <li>Heroku -- Enable ClearDB on Heroku</li>
      <pre>

         heroku addons:add cleardb:ignite 

      </pre>


      <li>Heroku -- Have PostGresQL addon</li>
      <pre>

         heroku addons:add postgresql:dev

      </pre>


      <br />
      <li>Local -- Have SQLite3 installed</li>
      <pre>
        you can run these examples with SQLite3
      </pre>
      <br />

      <li>Other (AWS, No SQLite3) -- Specify your database login (with these env variables)</li>
      <pre>
        MYSQL_USERNAME
        MYSQL_PASSWORD
        MYSQL_DB
        MYSQL_HOST
      </pre>
      </li>
  


    </div>


  </body>
</html>
