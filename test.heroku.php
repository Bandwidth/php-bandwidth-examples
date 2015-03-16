<?php

require_once("./lib/sqlite3fallback.php");


$res = exec("heroku config:get DATABASE_URL");
$db = new SQLite3Fallback2($res);

echo var_dump($db);
?>
