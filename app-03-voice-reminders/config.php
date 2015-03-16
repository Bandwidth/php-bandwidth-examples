<?php
/**
 * Config get the application
 * details
 */
require_once("helpers.php");
$application = json_decode(file_get_contents(realpath("./application.json")));
