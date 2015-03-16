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


// by default try running
// locally, this will check only SQLite
// and should work on
// 
// Apache HTTPd >= 2.2
// nGinx (not tested)
// lightppd
if (class_exists(SQLite3)) {
  // this means we're not running
  // with heroku cleardb, so we will
  // try SQLite3 when the class isn't
  // found try basic PDO
  
  // heroku by default will stop
  // running without this:

   require_once(__DIR__."/db.sqlite.php");
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
  if ($heroku_cdb) {

    // the following
    // should work on heroku
    $cleardb = parse_url($heroku_cdb);
    $username = $cleardb['username'];
    $password = $cleardb['password'];
    $db = substr($cleardb, 1);
    $host = $cleardb['host'];

    $db = new SQLite3Fallback($host, $username, $password, $db);
  } else {
    // simple mysqli fallback
    // make no assumption as to 
    // what we're using
    //
    //
    //
    //  TODO needs aws support
    $username = getenv("MYSQL_USERNAME");
    $password = getenv("MYSQL_PASS");
    $db = getenv("MYSQL_DB");
    $host = getenv("MYSQL_HOST");

    $db = new SQLite3Fallback($host, $username, $password, $db);

  }


}

// add exception
// for Heroku
if ($db->db == null || !$db->db) {
  exit("To run with Heroku please add ClearDB to your application");
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
  $res = $db->query("SELECT * FROM `" . $app['name'] . "`");
  if (!$res) {
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
