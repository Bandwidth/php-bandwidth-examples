<?php
/**
 * Makes our menu should include each
 * application found in these set of examples
 *
 * 1 - Requires bootstrap.php 
 *
 */

function generateMenu() {
  global $applications;
  printf("<ul class='left-nav'>");
  foreach ($applications as $app) {
    printf("<a href=%s>\n<li>%s</li></a>", "../" . $app['link'], $app['name']); 
  }
  printf("</ul>");
}
?>
