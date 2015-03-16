<?php

require_once("./lib/sqlite3fallback.php");

function pg_connection_string_from_database_url() {
  extract(parse_url($_ENV["DATABASE_URL"]));
  return "user=$user password=$pass host=$host dbname=" . substr($path, 1); # <- you may want to add sslmode=require there too
}

$db = new SQLite3Fallback2(pg_connection_string_from_database_url());
?>
