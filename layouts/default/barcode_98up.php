<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */

require_once('../classes/Lay.php');

class Layout_barcode_98up {
  public $p;
  function paramDefs() {
    return [['string', 'skip', ['title'=>'Skip Labels', 'default'=>'0']]];
  }
  function init($params) {
    $this->p = $params;
  }
  function render($rpt) {
    $lay = new Lay;
      $lay->container('Lines', ['margin-top'=>'0.25in', 'margin-bottom'=>'0.25in', 'margin-left'=>'0.40625in', 'margin-right'=>'0.46875in', 'x-spacing'=>'0.09375in']);
        $lay->container('Columns');
          [, $skip] = $this->p->getFirst('skip');
          for ($i = 0; $i < $skip; $i++) {
            $lay->container('Column', ['height'=>'0.75in', 'width'=>'1in']);
            $lay->close();
          }
          while ($row = $rpt->each()) {
            $lay->container('Column', ['height'=>'0.75in', 'width'=>'1in', 'y-align'=>'center']);
              $lay->container('TextLine', ['x-align'=>'center']);
                $lay->pushFont('Code39JK', 12);
                  $lay->text('*'.strtoupper((string) $row['barcode_nmbr']).'*');
                $lay->popFont();
              $lay->close();
              $lay->container('TextLine', ['margin-top'=>-3, 'x-align'=>'center']);
                $lay->pushFont('Code39JK', 12);
                  $lay->text('*'.strtoupper((string) $row['barcode_nmbr']).'*');
                $lay->popFont();
              $lay->close();
              $lay->container('TextLine', ['x-align'=>'center']);
                $lay->pushFont('Courier', 10);
                  $lay->text(strtoupper((string) $row['barcode_nmbr']));
                $lay->popFont();
              $lay->close();
            $lay->close();
          }
        $lay->close();
      $lay->close();
    $lay->close();
  }
}

?>
