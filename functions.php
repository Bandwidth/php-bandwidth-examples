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
        $sql = sprintf("CREATE TABLE %s ( ", $app['table']);
        $sub = "";
        foreach ($app['schema'] as $k=> $s) {
          if ($k == sizeof($app['schema']) - 1) {
            $sub.= RESERVED . $s[0] . RESERVED . " " . $s[1];
          } else {
            $sub.= RESERVED . $s[0] . RESERVED . " " . $s[1] . ",";
          }
        }
        $sql.=$sub;
        $sql .= "); ";
        
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

/**
 * When the application link
 * is active set the class
 *
 *  This generates the main menu
 * and will highlight according to what is selected
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

/**
 * generate a form
 * with the elements being either:
 *
 *
 * input, textarea, select or submit
 */
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
