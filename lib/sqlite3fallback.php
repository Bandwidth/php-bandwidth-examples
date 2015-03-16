<?php
// minimal func to 
// provide same wrapper as
// SQLite3 using a cleardb  

abstract class SQLiteFallbackCheck {
  public function isConnected() {
    if($this->db) {
      return TRUE;
    }
    return FALSE;
  }
}
final class SQLite3FallbackQuery {
  public function __construct($query) {
    $this->query = $query;
  }
  public function fetchAssoc() {
    return mysqli_fetch_assoc($this->query);
  }
  // provide
  // same function as sqlite3
  // fetchArray
  public function fetchArray() {
    if ($this->query) {
      return mysqli_fetch_array($this->query);
    }

    return array();
  }
}
final class SQLite3Fallback extends SQLiteFallbackCheck {
  // construct the
  // interface should
  // provide logic 
  // to create database
  public function __construct($host, $username, $password, $db) {
    $this->db = mysqli_connect($host, $username, $password, $db);
  }
  
  // forward a queyr 
  // to an SQlite3 like
  // interface with fetchArray/0
  public function query($query='') {
    if ($this->isConnected()) {
      $q = mysqli_query($this->db, $query);

      if ($q) {
        return new SQLite3FallbackQuery(mysqli_query($this->db, $query));
      }

      return FALSE;
    }
  }

  public function isConnected() {
    if ($this->db) {
      return true; 
    }
    return false;
  }
}

class SQLite3Fallback2Query {
  public function __construct($query) {
    $this->query = $query;
  }
  public function fetchArray() {
    return pg_fetch_row($this->query);
  }
}

class SQLite3Fallback2 extends SQLiteFallbackCheck {
  public function __construct($connection) { 
    $this->db = pg_connect($connection);
    $this->postgresql = true;
  }

  public function query($query) {
    $q = pg_query($query);
    if ($q) {
      return new SQLite3Fallback2Query($q);
    }

    return FALSE;
  }
}

?>
