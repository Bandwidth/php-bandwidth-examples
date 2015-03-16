<?php
/**
 * Config get the application
 * details
 */
require_once(__DIR__."/helpers.php");
$application = json_decode(file_get_contents(realpath(__DIR__."/application.json")));
