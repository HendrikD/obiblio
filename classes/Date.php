<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */
 
class Date {
  // Dates are represented internally as 'YYYY-mm-dd'
  function read_e($datestr, $ref=NULL) {
    $gotit = false;
    if (preg_match('/^([0-9][0-9][0-9][0-9])-([0-9]+)-([0-9]+)$/', (string) $datestr, $m)) {
      # Canonical (ISO 8601)
      $year = $m[1];
      $month = $m[2];
      $day = $m[3];
      $gotit = true;
    } elseif (preg_match('/^([0-9]+)[-\/]([0-9]+)[-\/]([0-9]+)$/', (string) $datestr, $m)) {
      # American Style
      $year = $m[3];
      $month = $m[1];
      $day = $m[2];
      if ($day > 12) {
        $gotit = true;
      }
      else {
        return [NULL, new ObibError('Ambiguous, use yyyy-mm-dd OR dd.mm.yyyy')];
      }
    } elseif (preg_match('/^([0-9]+)\.([0-9]+)\.([0-9]+)$/', (string) $datestr, $m)) {
      # European Style
      $year = $m[3];
      $month = $m[2];
      $day = $m[1];
      $gotit = true;
    }
    if ($gotit) {
      if ($month < 1 or $month > 12) {
        return [NULL, new ObibError('Bad month number: '.$month)];
      }
      if ($day < 1 or $day > 31) {
        return [NULL, new ObibError('Bad day number: '.$day)];
      }
      if ($year < 60) {
        $year += 2000;
      } elseif ($year < 100) {
        $year += 1900;
      }
      if (checkdate($month, $day, $year)) {
        return [sprintf('%04d-%02d-%02d', $year, $month, $day), NULL];
      } else {
        return [NULL, new ObibError('Invalid date, check your calendar')];
      }
    }
    if ($ref !== NULL) {
      [$ref, $err] = (new Date())->read_e($ref);
      if ($err) {
        return [NULL, $err];
      }
    } else {
      $ref = date('Y-m-d');
    }
    if ($datestr == 'today' or $datestr == 'now') {
      return [$ref, NULL];
    } elseif ($datestr == 'yesterday') {
      return [(new Date())->addDays($ref, -1), NULL];
    } elseif ($datestr == 'tomorrow') {
      return [(new Date())->addDays($ref, 1), NULL];
    } else {
      return [NULL, new ObibError('Invalid date format')];
    }
  }
  function addDays($date, $days) {
    $d = getdate(strtotime((string) $date));
    return date('Y-m-d', mktime(0, 0, 0, $d['mon'], $d['mday']+$days, $d['year']));
  }
  function addMonths($date, $months) {
    $d = getdate(strtotime((string) $date));
    return date('Y-m-d', mktime(0, 0, 0, $d['mon']+$months, $d['mday'], $d['year']));
  }
  function daysLater($d1, $d2) {
    $diff = round((strtotime((string) $d1)-strtotime((string) $d2))/86400);
    if ($diff > 0) {
      return $diff;
    } else {
      return 0;
    }
  }
  function getDays($since, $until) {
    $s = strtotime((string) $since);
    $u = strtotime((string) $until);
    assert($s <= $u);

    $since = date('Y-m-d', $s);
    $until = date('Y-m-d', $u);
    $days = [];
    for (; $since!=$until; $since=(new Date())->addDays($since, 1)) {
      array_push($days, $since);
    }
    array_push($days, $until);
    return $days;
  }
}

?>
