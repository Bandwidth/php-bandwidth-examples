<!DOCCTYPE HTML>
<html>
  <head>
  <?php require_once(__DIR__."/../bootstrap.php"); ?>
  <?php
  // when we're already
  // connected do not
  // come back here!
  if (isDbConnected()) {
    route("./");
  }
  ?>

  </head>
  <body>
    <div style="border: 1px solid #E3E3E3; padding: 20px; ">
    <h2>Error - Database connection<h2>
      <hr />
      <small><b>In order to run php-bandwidth-examples you must connect with ONE of the following</small>
      <br />
      <br />

      <li>Heroku -- Enable ClearDB on Heroku</li>
      <pre>

         heroku addons:add cleardb:ignite 

      </pre>


      <li>Heroku -- Enable PostGresQL addon</li>
      <pre>

         heroku addons:add postgresql:dev

      </pre>


      <br />
      <li>anywhere else -- Have SQLite3 built with PHP >= 5.3</li>
      <small>If you're running locally and do not want to use a larger database, you can always build PHP with sqlite3</small>
      <pre>
          ./configure --enable-sqlite3
      </pre>
      <br />
      <br />

      <li>Other (AWS, No SQLite3) -- Specify your database login (with these env variables)</li>
      <pre>
        MYSQL_USERNAME
        MYSQL_PASSWORD
        MYSQL_DB
        MYSQL_HOST
      </pre>
      </li>
  

      <small>Have you updated your setup? </small>
      <a class="btn-primary" href="./">Go back</a>

    </div>


  </body>
</html>
