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
  $specials = implode("|",array("home", "transactions"));
  if (preg_match("/((?:app-[\w\d\-]+)|($specials))/", $url, $matches)) {
    // add the home link
    //
    if (isset($matches[2])) {
      printf("<a href='%s'>\n<li class='active'>%s</li></a>", getHomeLink(), "Home");
    } else {
      printf("<a href='%s'>\n<li>%s</li></a>", getHomeLink(), "Home");
    }

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

/**
 * takes out all 
 * of the string aside from
 * php-bandwidth-examples
 *
 * {host}/php-bandwidth-examples
 */
function stripLocation($other) {
  $url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
  $url = preg_replace("/\/(home.*|app.*)$/", "", $url);

  return "http://" . $url . "/" . $other;
}

/**
 * get the home link relative
 * to where we are
 *
 */
function getHomeLink() {
   // in an app ../
   // in home ./
   $url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; 
   if (preg_match("/app/", $url, $m)) {
      return "../home";
   }

   return "./home";
}


/**
 * check if our uri matches
 *
 */
function isPage($url) {
  $m = array();
  $url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
  preg_match("/\/(.*)$/", $url, $m);
  if ($m[1] == $url) {
    return true;
  }
  return false;
}

/**
 * library friendly route
 * use javascript in acheiving
 */
function route($area) {
  printf("<script>top.location.href=\"%s\"</script>",$area);
}

/**
 * alert then remove the message
 * tag.
 */
function alert($message) {
  printf(sprintf("<script>
   alert('%s');
   var location = document.location.href;
  document.location.replace(document.location.href.replace(/\?message=.*/, '')) + '#';

   </script>", $message));
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
  // add a unique id to the form
  // name to allow multiple forms
  // on the same page without having name
  // conflicts
  $html .= sprintf("<form name='mainFrm_%s' method='POST' action='./form.php'>", uniqid(true));
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

/**
 * only need to load
 * things when we're given
 * the index
 */
function isIndex() {
  $page = $_SERVER['PHP_SELF'];
  $m = array();
  if (preg_match("/index.php$|\/$/", $page, $m)) {
    return true;
  }
  return false;
}

/**
 * check if our database
 * is connected
 *
 */
function isDbConnected() {
  global $db;

  if ($db) {
    if ($db->postgresql) {
      if ($db->db) {
        return true;
      }
    } else {
      if ($db) {
        return true;
      }
    }
  }
  return false;
}


?>
