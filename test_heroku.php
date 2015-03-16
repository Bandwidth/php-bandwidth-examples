<?php



require_once("./lib/sqlite3fallback.php");


extract(parse_url(getenv("DATABASE_URL")));


$db = new SQLite3Fallback2("user=$user password=$password host=$host dbname=" . substr($path, 1));

?>
