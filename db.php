<?php

require_once(__DIR__."/bootstrap.php");
require_once(__DIR__."/tables.php");
require_once(__DIR__."/config.php");

define("DB_TILDE","`");
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

if (false) {
  // we have native
  // support for 
  // SQLite3
  $db = new SQLite3(__DIR__ . "/" . $application->sqliteDatabaseFile, SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE);
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

  if (TRUE) {

    require_once(__DIR__ . "/lib/sqlite3fallback.php");
    $username = "root";
    $password = "";
    $db = "test_heroku";
    $host = "localhost";
    // the following
    // should work on heroku
    /*
    $cleardb = parse_url(getenv("CLEARDB_DATABASE_URL"));
    $username = $cleardb['username'];
    $password = $cleardb['password'];
    $db = substr($cleardb, 1);
    $host = $cleardb['host']
    */

    $db = new SQLite3Fallback($host, $username, $password, $db);
  } else {
    // simple mysqli fallback
    // make no assumption as to 
    // what we're using
    //
    //  TODO

  }

}


$cols = array(
  "`from`", "`to`", "`meta`", "`date`"
);

/**
 * Let each table have: date, from, url and to fields
 * some applications will need a url field.
 *
 * meta is general purpose and will be used for 
 * the data in each application
 */
foreach (array_merge($applications, $tables) as $app) {
  if ($db->query("SELECT * FROM `" . $app['name'] . "`") == false) {
    if (isset($app['schema'])) {
      $sql = $app['schema'];
    } else {
      $sql = "CREATE TABLE `" . $app['name'] . "` (
        `from` VARCHAR(255),
        `to` VARCHAR(255),
        `meta` VARCHAR(255),
        `date` VARCHAR(255) 
      );";
    }

    $result = $db->query($sql);
  }

}

function updateRecord() {
  global $db;
  global $cols;
}

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
    $strcols = DB_TILDE . implode($colsarray, DB_TILDE . "," . DB_TILDE) . DB_TILDE;
  } else {
    $strcols = implode($cols, ",");
  } 
  foreach ($recordarray as $r) {
    $recstr .= "'$r',";
  } 
  $recstr = preg_replace("/,$/", "", $recstr);

  $q = "INSERT INTO `$apptable` ($strcols) VALUES ($recstr); ";

  $result = $db->query($q); 
} 

function addRecordBasic($apptable, $array) {
  return addRecord($apptable, $array, array('from', 'to', 'meta', 'date'));
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

?>
