<?php
/**
 * Includes all the CSS/JS used for these applications
 * feel free to add or change.
 *
 * files includes in 
 * {top_level}/js
 * {top_level}/css
 *
 *
 * This file is included throughout the examples. Also
 * associated with setup.json which is the database
 * variables. By default we use SQLite 3.
 *
 */


$files = array(
  array(
    "type" => "css",
    "file" => "bootstrap.min"
  ),
  array(
    "type" => "css",
    "file" => "main"
  ),
  array(
    "type" => "js",
    "file" => "jquery"
  ),
  array(
    "type" => "js",
    "file" => "bootstrap.min"
  ),
  
);

require_once(__DIR__ . "/config.php");
require_once(__DIR__ . "/functions.php");
// include the sqLite database

if (!isConnected()) {
  // when we have
  // an error don't
  // route
  if (preg_match("/error/", $_SERVER{"REQUEST_URI"}, $m) == null) {
    route(stripLocation("home/error.api.php"));
  }
}

if (isIndex()) {
  foreach ($files as $file) {
    if ($file['type']=='js') {
      printf("<script src='%s' type='text/javascript'></script>\n", "../".$file['type']."/".$file['file'].".".$file['type']);
    } else {
      printf("<link rel='stylesheet' href='%s' />\n", "../".$file['type']."/".$file['file'].".".$file['type']);
    }
  }
}
