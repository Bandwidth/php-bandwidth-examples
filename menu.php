<?php
/**
 * Makes our menu should include each
 * application found in these set of examples
 *
 * 1 - Requires bootstrap.php 
 *
 */

/**
 * When the application link
 * is active set the class
 */
function generateMenu() {
  global $applications;
  printf("<ul class='left-nav'>");
  $url = $_SERVER['PHP_SELF'];
  $matches = array();
  if (preg_match("/((?:app-[\w\d\-]+)|(home))/", $url, $matches)) {
    foreach ($applications as $app) {
      if ($app['link'] == $matches[1]) {  
        printf("<a href=%s>\n<li class='active'>%s</li></a>", "../" . $app['link'], $app['name']); 
      } else {
        printf("<a href=%s>\n<li>%s</li></a>", "../" . $app['link'], $app['name']); 
      }
    }
  }
  printf("</ul>");
}

function route($area) {
  printf("<script>top.location.href='%s'</script>",$area);
}

function generateForm($elements) {
  $html = "";
  $html .= sprintf("<form name='' method='POST' action='./form.php'>");
  foreach ($elements as $k => $el) {
    if ($el['type'] !== 'submit') {
      $html .= sprintf("<label>%s</label>", $k);
    }
    if ($el['type']=='text' || $el['type'] == 'input') {

      $html .= sprintf("<input class='form-control' name='%s' placeholder='%s' />", $el['name'], $el['placeholder']);

    } elseif ($el['type'] == 'textarea') {
       $html .= sprintf("<textarea class='form-control' name='%s'>%s</textarea>",$el['name'], $el['placeholder']);
    
    } elseif ($el['type'] == 'select') {

      $html .= sprintf("<select class='form-control' name='%s'>", $el['name']);
      foreach ($el['children'] as $c) {
        $html .= sprintf("<option value='%s'>%s</option>", $c[$el['field_id']], $c[$el['field_name']]);
      }
    } elseif ($el['type'] == 'submit') {
      $html .= sprintf("<input type='submit' value='%s' />", $k);
    }
  }

  $html .= sprintf("</form>");

  return $html;
}
?>
