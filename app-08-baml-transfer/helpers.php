<?php

/**
 * escape our XML content
 */
function xml_entities($string) {
  return str_replace(
      array("&",     "<",    ">",    '"',      "'"),
      array("&amp;", "&lt;", "&gt;", "&quot;", "&apos;"), 
      $string
  );
}


?>
