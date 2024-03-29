<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */

/* Fatal Errors */
class Fatal {
  /* Override default behaviour, e.g. for supressing errors, unit testing, etc. */
  static function setHandler(&$obj) {
    global $_Error_FatalHandler;
    $old =& $_Error_FatalHandler;
    $_Error_FatalHandler = $class;
    return $old;
  }
  /* "Can't happen" states */
  static function internalError($msg) {
    global $_Error_FatalHandler;
    if (method_exists($_Error_FatalHandler, 'internalError')) {
      $_Error_FatalHandler->internalError($msg);
    } else {
      (new Fatal())->error('Internal Error: '.$msg);
    }
  }
  /* Query errors */
  static function dbError($sql, $msg, $dberror) {
    global $_Error_FatalHandler;
    if (method_exists($_Error_FatalHandler, 'dbError')) {
      $_Error_FatalHandler->dbError($sql, $msg, $dberror);
    } else {
      (new Fatal())->error('Database Error: '.$msg.' in query: '.$sql.' DBMS says: '.$dberror);
    }
  }
  /* Generic */
  static function error($msg) {
    global $_Error_FatalHandler;
    if (method_exists($_Error_FatalHandler, 'error')) {
      $_Error_FatalHandler->error($msg);
    } else {
      die($msg);
    }
  }
}
?>