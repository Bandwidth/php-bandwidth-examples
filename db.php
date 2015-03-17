<?php

require_once(__DIR__."/bootstrap.php");
require_once(__DIR__."/tables.php");
require_once(__DIR__."/config.php");
require_once(__DIR__."/functions.php");

$application = json_decode(file_get_contents(__DIR__ . "/setup.json"));
/**
 * Basic DB wrapper around Catapult examples
 *
 * Provide minimal functions to open close and 
 * add transactions
 */



/**
 * Create a database in the root
 * directory. This should have read
 * and write access.
 *
 * To change the file being used for the databse
 * please go to ./setup.json
 *
 */


// by default try running
// locally, this will check only SQLite
// and should work on
// 
// Apache HTTPd >= 2.2
// nGinx (not tested)
// lightppd
if (class_exists(SQLite3)) {
  // this means we're not running
  // with heroku cleardb, nor do we need
  // mysql so we will run with
  // the native SQLite3 wrapper
  
   $db = new SQLite3(__DIR__ . "/" . $application->sqliteDatabaseFile, SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE);

   define("DB_TILDE", "`");
} else {
  // either heroku or
  // without SQlite3
  // we can use ClearDB 
  // here

  // Note:
  //
  // to run on Heroku please use:
  //
  // heroku config:set ON_HEROKU=1
  //
  //
  // or if on AWS
  // ON_AWS
  //
  // as this will use the clearDB
  // configuration

  require_once(__DIR__ . "/lib/sqlite3fallback.php");
  $heroku_cdb = getenv("CLEARDB_DATABASE_URL");

  // use heroku postgresql
  $heroku_post = getenv("DATABASE_URL");

  if ($heroku_post) {
    // use the heroku postgresql
    // support 
    $postdb = extract(parse_url($heroku_post));
    $db = new SQLite3Fallback2("user=$user password=$pass host=$host dbname=" . substr($path, 1));
    define("DB_TILDE", "");
  } elseif ($heroku_cdb) {

    // the following
    // should work on heroku
    $cleardb = parse_url($heroku_cdb);
    $username = $cleardb['username'];
    $password = $cleardb['password'];
    $db = substr($cleardb, 1);
    $host = $cleardb['host'];

    $db = new SQLite3Fallback($host, $username, $password, $db);

    // for clearDB
    // we use mySQL which
    // needs the infixes for reserved variables
    define("DB_TILDE", "`");
  } else {
    // simple mysqli fallback
    // make no assumption as to 
    // what we're using
    // for AWS
    //  TODO needs aws support
    $username = getenv("MYSQL_USERNAME");
    $password = getenv("MYSQL_PASS");
    $db = getenv("MYSQL_DB");
    $host = getenv("MYSQL_HOST");

    $db = new SQLite3Fallback($host, $username, $password, $db);

    define("DB_TILDE", "`");
  }


  // when we have no db
  // past this point, we will
  // still try to run, warn and store
  // no data
  if (!$db->isConnected()) {
    header("location: ./home/error.db.php");
  }


}
if ($db->postgresql) {
  define("RESERVED", '"');
} else {
  define("RESERVED", DB_TILDE);
}


makeTables($db, $applications, $tables);


/**
 * add a record in one of the  application or data 
 * tables
 *
 * @param recordarray: array with no key context
 */
function addRecord($apptable, $recordarray, $colsarray=null) {
  global $db;
  global $cols;
  $recstr = '';
  $valstr = '';
  if (is_array($colsarray)) {
    $strcols = RESERVED . implode($colsarray, RESERVED . "," . RESERVED) . RESERVED;
  } else {
    $strcols = implode($cols, ",");
  } 
  foreach ($recordarray as $r) {
    $recstr .= "'$r',";
  } 
  $recstr = preg_replace("/,$/", "", $recstr);

  $q = "INSERT INTO $apptable ($strcols) VALUES ($recstr); ";
 
  $result = $db->query($q); 
} 

function addRecordBasic($apptable, $array) {
  return addRecord($apptable, $array, array('from', 'to', 'meta', 'date'));
}

// no logic 
// here will execute query
function updateRow($expr) {
  global $db;
  return $db->query($expr);
}

function getCount($expr) {
  global $db;

  $q = $db->query($expr);
  while ($r = $q->fetchArray()) {
    return $r['count'];
  }
}

function getKey($expr, $key) {
  global $db;

  $q = $db->query($expr);
  while ($r = $q->fetchArray()) {
    return $r[$key];
  }
}

function getRow($expr) {
  global $db;
  $q = $db->query($expr);
  while ($r = $q->fetchArray()) {
    return $r;
  }
  return $null;
}

function getRows($expr) {
  global $db;
  $q = $db->query($expr);
  $rows = array();
  while ($row = $q->fetchArray()) {
    $rows[]=$row;
  }
  return $rows;
}

function getQuery($expr) {
  global $db;
  $q = $db->query($expr);

  return $q;
}


?>
