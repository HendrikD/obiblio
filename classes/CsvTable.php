<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */
 
require_once("../functions/inputFuncs.php");

class CsvTable {
  public $_cols = [];
  function escape($str) {
    if (strcspn((string) $str, ";\"\r\n") != strlen((string) $str)) {
      $str = '"'.str_replace('"', '""', (string) $str).'"';
    }
    return $str;
  }
  function parameters($params) {
  }
  function columns($cols) {
    $this->_cols = array_merge($this->_cols, $cols);
  }
  function start() {
    $arr = [];
    foreach ($this->_cols as $col) {
      if (!isset($col['title']) or !$col['title']) {
        $col['title'] = $col['name'];
      }
      $arr[] = $this->escape($col['title']);
    }
    echo implode(';', $arr)."\r\n";
  }
  function row($row) {
    $arr = [];
    foreach ($this->_cols as $col) {
      $arr[] = $this->escape($row[$col['name']]);
    }
    echo implode(';', $arr)."\r\n";
  }
  function end() {
  }
}

?>
