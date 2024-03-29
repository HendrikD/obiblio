<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */

require_once('../classes/Lay.php');

class Layout_labels {
  public $p;
  function paramDefs() {
    return [['string', 'skip', ['title'=>'Skip Labels', 'default'=>'0']]];
  }
  function init($params) {
    $this->p = $params;
  }
  function render($rpt) {
    $lay = new Lay;
      $lay->container('Lines', ['margin-top'=>'0.5in', 'margin-bottom'=>'0.5in', 'margin-left'=>'0.0', 'margin-right'=>'0.0in']);
        $lay->container('Columns');
          [, $skip] = $this->p->getFirst('skip');
          for ($i = 0; $i < $skip; $i++) {
            $lay->container('Column', ['height'=>'1in', 'width'=>'2.8333in']);
            $lay->close();
          }
          while ($row = $rpt->each()) {
            $lay->container('Column', ['height'=>'1in', 'width'=>'2.8333in', 'y-align'=>'center']);
              $lay->container('TextLine', ['x-align'=>'center']);
                $lay->pushFont('Times-Roman', 10);
                  if (strlen((string) $row['title']) > 30) {
                    $row['title'] = substr((string) $row['title'], 0, 30)."...";
                  }
                  $lay->text($row['title']);
                $lay->popFont();
              $lay->close();
              $lay->container('TextLine', ['x-align'=>'center']);
                $lay->pushFont('Code39JK', 24);
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
