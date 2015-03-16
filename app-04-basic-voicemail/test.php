<?php
require_once("../config.php");

$client = new Catapult\Client;
$files = scandir("./data");
$file =  $files[2];

$media = new Catapult\MediaCollection;
$coll = $media->listAll(array("size"=> 1000));
$rows = getRow("SELECT * FROM `Basic Voice Mail Data`;");
echo var_dump($rows);


?>
