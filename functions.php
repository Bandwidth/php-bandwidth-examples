<?php

// generic things
// used through the 
// examples


/**
 * Let each table have: date, from, url and to fields
 * some applications will need a url field.
 *
 * meta is general purpose and will be used for 
 * the data in each application
 */
function makeTables($db, $applications, $tables) {
  foreach (array_merge($applications, $tables) as $app) {
    $res = $db->query("SELECT * FROM " . DB_TILDE . "" . $app['table'] . "".  DB_TILDE);
    if (!$res) {
      if (isset($app['schema'])) {
        $sql = $app['schema'];
        
      } else {

        $sql = "CREATE TABLE " . $app['table'] . " (
          " . RESERVED . "from"  . RESERVED . " VARCHAR(255),
          " . RESERVED . "to"  .  RESERVED . " VARCHAR(255),
          " . RESERVED . "meta"  .  RESERVED . " VARCHAR(255),
          " . RESERVED . "date"  .  RESERVED . " VARCHAR(255)
        );";
      }

      $result = $db->query($sql);
    }
  }
}

?>
