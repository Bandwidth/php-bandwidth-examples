<?php

// Load SQlite3 
// externally to prevent
// heroku's default error logging
// level from terminating application

$db = new SQLite3(__DIR__ . "/" . $application->sqliteDatabaseFile, SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE);
?>
