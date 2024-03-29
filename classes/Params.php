<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */

require_once("../functions/inputFuncs.php");
require_once("../classes/Date.php");
 
class Params {
  public $dict = [];
  function load_el($paramdefs, $params) {
    return $this->_load_el($this->dict, $paramdefs, $params);
  }
  function loadCgi_el($paramdefs, $prefix='rpt_') {
    $params = [];
    $preflen = strlen((string) $prefix);
    foreach ($_REQUEST as $k => $v) {
      if (substr($k, 0, $preflen) == $prefix) {
        $params[substr($k, $preflen)] = $v;
      }
    }
    $el = $this->_load_el($this->dict, $paramdefs, $params);
    $errs = [];
    foreach ($el as $k => $e) {
      $errs[$prefix.$k] = $e;
    }
    return $errs;
  }
  /* Careful! */
  function loadDict($dict) {
    $this->dict = array_merge($this->dict, $dict);
  }
  function exists($name) {
    return $this->getFirst($name) != false;
  }
  function getFirst($name) {
    $l = $this->getList($name);
    if (isset($l[0])) {
      return $l[0];
    } else {
      return NULL;
    }
  }
  function getList($name) {
    $values = [['group', $this->dict]];
    foreach ($this->_splitName($name) as $n) {
     $dicts = [];
      foreach($values as $v) {
        if ($v[0] == 'group') {
          $dicts[] = $v[1];
        }
      }
      $values = [];
      foreach ($dicts as $d) {
        if (isset($d[$n]) and $d[$n]) {
          $v = $d[$n];
          if ($v[0] == 'list') {
            foreach ($v[1] as $val) {
              $values[] = $val;
            }
          } else {
            $values[] = $v;
          }
        }
      }
    }
    return $values;
  }
  function set($name, $type, $value) {
    $path = $this->_splitName($name);
    $n = array_pop($path);
    $dict =& $this->dict;
    foreach ($path as $p) {
      $v =& $dict[$p];
      if ($v[0] == 'list') {
        $v =& $v[1][0];
      }
      if ($v[0] != 'group') {
        $dict[$p] = ['group', []];
        $v =& $dict[$p];
      }
      $dict =& $v[1];
    }
    $dict[$n] = [$type, $value];
  }
  function copy() {
    $p = new Params;
    $p->loadDict($this->dict);
    return $p;
  }
  /* STATIC */
  function printForm($defs, $prefix='rpt_', $namel=[]) {
    echo '<table class="'.$prefix.'params">';
    foreach ($defs as $def) {
      $def = array_pad($def, 4, NULL);		# Sigh.
      [$type, $name, $options, $list] = $def;
      $l = array_merge($namel, [$name]);
      if (isset($options['repeatable']) && $options['repeatable']) {
        for ($i=0; $i<4; $i++) {
          (new Params())->_print($type, array_merge($l, [$i]), $options, $list, $prefix);
        }
      } else {
        (new Params())->_print($type, $l, $options, $list, $prefix);
      }
    }
    echo '</table>';
  }
  /* PRIVATE */
  function _print($type, $namel, $options, $list, $prefix) {
    global $loc;
    assert($loc);
    assert(!empty($namel));
    if ($type == 'session_id') {
      return;
    }
    if (isset($options['hidden'])) {
      return;
    }
    if ($type == 'group') {
      echo '<tr><td class="'.$prefix.'group" colspan="2">';
      (new Params())->printForm($list, $prefix, $namel);
      echo '</td></tr>';
      return;
    }
    if ($type == 'order_by') {
      $title = 'Sort By';
    } elseif (isset($options['title']) && $options['title']) {
      $title = $options['title'];
    } else {
      $title = $namel[(is_countable($namel) ? count($namel) : 0)-1];
    }
    $name = $prefix . array_shift($namel);
    foreach ($namel as $n) {
      $name .= '['.$n.']';
    }
    if (isset($options['default'])) {
      $default = $options['default'];
    } else {
      $default = '';
    }
    echo '<tr class="'.$prefix.'param">';
    echo '<td><label for="'.H($name).'">';
    echo $loc->getText($title);
    echo '</label></td><td>';
    switch ($type) {
    case 'string':
    case 'date':
      echo inputField('text', $name, $default);
      break;
    case 'select':
      $l = [];
      foreach ($list as $v) {
        [$n, $o] = $v;
        if (isset($o['title']) && $o['title']) {
          $l[$n] = $loc->getText($o['title']);
        } else {
          $l[$n] = $n;
        }
      }
      echo inputField('select', $name, $default, NULL, $l);
      break;
    case 'order_by':
      $l = [];
      foreach ($list as $v) {
        [$n, $o] = $v;
        if (isset($o['title']) and $o['title']) {
          $l[$n] = $loc->getText($o['title']);
        } else {
          $l[$n] = $n;
        }
        $l[$n.'!r'] = $l[$n].' ('.$loc->getText("Reverse").')';
      }
      echo inputField('select', $name, $default, NULL, $l);
      break;
    default:
      assert(NULL);
    }
    echo '</td></tr>';
  }
  function _splitName($name) {
    return explode('.', (string) $name);
  }
  function _load_el(&$parameters, $paramdefs, $params, $errprefix=NULL) {
    $errs = [];
    foreach ($paramdefs as $p) {
      $p = array_pad($p, 4, NULL);		# Sigh.
      [$type, $name, $options, $list] = $p;
      if (is_null($errprefix)) {
        $errnm = $name;
      } else {
        $errnm = $errprefix.'['.$name.']';
      }
      if (isset($options['default'])
          and !isset($parameters[$name])
          and !isset($params[$name])) {
        $params[$name] = $options['default'];
      }
      if (isset($parameters[$name]) and !isset($params[$name])) {
        continue;
      }
      if (isset($options['repeatable']) and $options['repeatable']
          and is_array($params[$name])) {
        $l = [];
        foreach ($params[$name] as $idx => $it) {
          [$v, $el] = $this->_mkParam_el($it, $type, $options, $list, $errnm.'['.$idx.']');
          $errs = array_merge($errs, $el);
          if ($v) {
            $l[] = $v;
          }
        }
        if (!empty($l)) {
          $parameters[$name] = ['list', $l];
        } else {
          # A false, but "set" value so that it won't be reset to the default later.
          $parameters[$name] = '';
        }
      } else {
        [$val, $el] = $this->_mkParam_el($params[$name], $type, $options, $list, $errnm);
        $errs = array_merge($errs, $el);
        if ($val) {
          $parameters[$name] = $val;
        } else {
          # A false, but "set" value so that it won't be reset to the default later.
          $parameters[$name] = '';
        }
      }
    }
    return $errs;
  }
  function _mkParam_el($val, $type, $options, $list, $errprefix) {
    $noerrors = [];
    switch ($type) {
      case 'string':
        $val = trim((string) $val);
        if (strlen($val) != 0) {
          return [['string', $val], $noerrors];
        }
        break;
      case 'date':
        $val = trim((string) $val);
        if (!empty($val)) {
          [$val, $error] = (new Date())->read_e($val);
          if ($error) {
            return [NULL, [$errprefix=>$error]];
          }
          return [['string', $val], $noerrors];
        }
        break;
      case 'select':
        foreach ($list as $v) {
          if ($val == $v[0]) {
            return [['string', $v[0]], $noerrors];
          }
        }
        break;
      case 'group':
        $dict = [];
        $el = $this->_load_el($dict, $list, $val, $errprefix);
        if (!empty($el)) {
          return [NULL, $el];
        }
        if (isset($dict[$options['must_have']])
            or !$options['must_have'] and !empty($dict)) {
          return [['group', $dict], $noerrors];
        }
        break;
      case 'session_id':
        return [['string', session_id()], $noerrors];
      case 'order_by':
        $rawval = $val;
        $desc = ' ';
        if (preg_match('/!r$/', (string) $val)) {
          $desc = ' desc ';
          $val = substr((string) $val, 0, -2);
        }
        $expr = $this->getOrderExpr($val, $list, $desc);
        return [['order_by', $expr, $rawval], $noerrors];
      default:
        assert(NULL);		# Can't happen
    }
    return [NULL, $noerrors];
  }
  function getOrderExpr($name, $list, $desc) {
    $expr = false;
    foreach ($list as $v) {
      if ($v[0] != $name) {
        continue;
      }
      if (isset($v[1]['expr']) and $v[1]['expr']) {
        $expr = $v[1]['expr'];
      } else {
        $expr = $name;
      }
      if (!isset($v[1]['type']) or !$v[1]['type']) {
        $v[1]['type'] = 'alnum';
      }
      switch ($v[1]['type']) {
      case 'MARC':
        if (!isset($v[1]['skip_indicator'])) {
          (new Fatal())->internalError("MARC sort without skip indicator");
        }
        $skip = $v[1]['skip_indicator'];
        $expr = "ifnull(substring($expr, $skip+1), $expr)";
        /* fall through */
      case 'alnum':
        $expr = "if($expr regexp '^ *[0-9]', "
                . "concat('0', ifnull(floor(log10($expr)), 0), "
                . "$expr), $expr)".$desc;
        break;
      case 'multi':
        $sorts = explode(',', (string) $expr);
        $expr = '';
        foreach ($sorts as $s) {
          $expr .= ', '.$this->getOrderExpr(trim($s), $list, $desc);
        }
        if ($expr) {
          $expr = substr($expr, 2);	# Lose initial ', '
        }
        break;
      default:
        $expr .= $desc;
        break;
      }
      break;
    }
    if (!$expr) {
      return '1'; /* constant expr means no particular order */
    } else {
      return $expr;
    }
  }
}

?>
