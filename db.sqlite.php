<?php

// Load SQlite3 
// externally to prevent
// heroku's default error logging
// level from terminating application
if (!class_exists(SQLite3)) {
  class SQLite3 {};
  exit("No support for SQLite3 here..");
}

$db = new SQLite3(__DIR__ . "/" . $application->sqliteDatabaseFile, SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE);
?>
