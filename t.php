if (is_array($colsarray)) {
    $strcols = RESERVE . implode($colsarray, RESERVED; . "," . RESERVED) . RESERVED;
  } else {
    $strcols = implode($cols, ",");
  } 
  foreach ($recordarray as $r) {
    $recstr .= "'$r',";
  } 
  $recstr = preg_replace("/,$/", "", $recstr);

  $q = "INSERT INTO " . RESERVED; . "$apptable" . RESERVED . "($strcols) VALUES ($recstr); ";

