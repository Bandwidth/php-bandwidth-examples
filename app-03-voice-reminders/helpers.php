<?php

/**
 * get which column we're
 * in since we're going voice reminders
 * we should mock a sequence flow
 * our cols variable marks this and 
 * will be used through our helpers
 *
 * @param  record: an SQLite record
 */
$cols = array("speech", "year", "month", "date", "thanks");

function getCol($record) {
  global $cols;
  foreach ($cols as $col) {
    if ($record[$col] == null) {
      return $col;
    }
  }
  return 1;
}

/**
 * find our next column
 * on empty should return NULL
 * 
 * @param col: a column in the SQlite Application table
 */
function getNextCol($col) {
  global $cols;
  return $cols[getArrayKey($cols, $col) + 1];
}

function getArrayKey($array, $col) {
  foreach ($array as $c => $a) {
    if ($col == $a) {
      return $c; 
    }
  }
  return 0;
}


/**
 * get a php friendly DateTime
 * our of our SQLite findings
 */
function makeDate($data) {
  $date = new DateTime;
  $time = strtotime(sprintf("%d-%d-%d",$data['year'],$data['month'],$data['date']));

  $date->setTimestamp($time);

  return $date;
}
