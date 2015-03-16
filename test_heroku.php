<?php

require_once("./db.php");
// test
//

$db->query("INSERT INTO `sms_auto_replies` ('from', 'to', 'date', 'meta') ('', '', '', ''); ");


$q = pg_query("SELECT * FROM sms_auto_replies; ");
while ($row = pg_fetch_assoc($q)) {
echo var_dump($row);
}


?>
