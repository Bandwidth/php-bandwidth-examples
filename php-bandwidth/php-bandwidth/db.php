<?php

require_once(__DIR__."/bootstrap.php");
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
$db = new SQLite3(__DIR__ . "/" . $application->sqliteDatabaseFile,SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE);
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
foreach ($applications as $app) {
  $hastable = false;
  if ($db->query("SELECT * FROM `" . $app['name'] . "`") == false) {
    $sql = "CREATE TABLE `" . $app['name'] . "` (
      `from` VARCHAR(255),
      `to` VARCHAR(255),
      `meta` VARCHAR(255),
      `date` VARCHAR(255) 
    );";

    $result = $db->query($sql);
  }

}



/**
 * add a record in one of four application
 * tables
 *
 * @param recordarray: array with no key context
 */
function addRecord($apptable, $recordarray) {
  global $db;
  global $cols;
  $recstr = '';
  $valstr = '';
  $strcols = implode($cols, ",");
  foreach ($recordarray as $r) {
    $recstr .= "'$r',";
  } 
  $recstr = preg_replace("/,$/", "", $recstr);

  $q = "INSERT INTO `$apptable` ($strcols) VALUES ($recstr); ";

  $result = $db->query($q); 

} 
?>
