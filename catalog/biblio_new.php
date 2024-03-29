<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */

require_once("../shared/common.php");

$tab = "cataloging";
$nav = "new";

require_once("../classes/Localize.php");
$loc = new Localize(OBIB_LOCALE,$tab);

if (!isset($_REQUEST['posted'])) {
  require_once("../shared/logincheck.php");
  showForm(['opacFlg'=>'CHECKED']);
} else {
  $postVars = $_POST;
  if ($_REQUEST['posted'] == 'media_change') {
    require_once("../shared/logincheck.php");
    showForm($postVars);
  } else {
    $restrictInDemo = true;
    require_once("../shared/logincheck.php");
    $biblio = postVarsToBiblio($postVars);
    $pageErrors = [];
    if (!$biblio->validateData()) {
      $pageErrors = array_merge($pageErrors, biblioToPageErrors($biblio));
    }
    $pageErrors = array_merge($pageErrors, customFieldErrors($biblio));
    if (!empty($pageErrors)) {
     showForm($postVars, $pageErrors);
    } else {
      $bibid = insertBiblio($biblio);
      $msg = $loc->getText("biblioNewSuccess");
      header("Location: ../shared/biblio_view.php?bibid=".U($bibid)."&msg=".U($msg));
    }
  }
}

function postVarsToBiblio($post) {
  require_once("../classes/Biblio.php");
  require_once("../classes/BiblioField.php");
  
  $biblio = new Biblio();
  $biblio->setMaterialCd($post["materialCd"]);
  $biblio->setCollectionCd($post["collectionCd"]);
  $biblio->setCallNmbr1($post["callNmbr1"]);
  $biblio->setCallNmbr2($post["callNmbr2"]);
  $biblio->setCallNmbr3($post["callNmbr3"]);
  $biblio->setLastChangeUserid($_SESSION["userid"]);
  $biblio->setOpacFlg(isset($post["opacFlg"]));
  $indexes = $post["indexes"];
  foreach($indexes as $index) {
    $value = $post["values"][$index];
    $fieldid = $post["fieldIds"][$index];
    $tag = $post["tags"][$index];
    $subfieldCd = $post["subfieldCds"][$index];
    $requiredFlg = $post["requiredFlgs"][$index];
    $biblioFld = new BiblioField();
    $biblioFld->setFieldid($fieldid);
    $biblioFld->setTag($tag);
    $biblioFld->setSubfieldCd($subfieldCd);
    $biblioFld->setIsRequired($requiredFlg);
    $biblioFld->setFieldData($value);
    $biblio->addBiblioField($index,$biblioFld);
  }
  return $biblio;
}
function biblioToPageErrors($biblio) {
  $pageErrors = [];
  $pageErrors["callNmbr1"] = $biblio->getCallNmbrError();
  $biblioFlds = $biblio->getBiblioFields();
  foreach($biblio->getBiblioFields() as $index => $field) {
    if ($field->getFieldDataError() != "") {
      $pageErrors[$index] = $field->getFieldDataError();
    }
  }
  return $pageErrors;
}
function customFieldErrors($biblio) {
  require_once("../classes/MaterialFieldQuery.php");
  $matQ = new MaterialFieldQuery();
  $matQ->connect();
  $rows = $matQ->get($biblio->getMaterialCd());
  $matQ->close();
  $errors = [];
  $fields = $biblio->getBiblioFields();
  foreach ($rows as $row) {
    $idx = sprintf('%03d%s', $row['tag'], $row['subfieldCd']);
    if ($row['required'] == 'Y') {
      if (!isset($fields[$idx]) or $fields[$idx]->getFieldData() == '') {
        $errors[$idx] = 'Field is required.';
      }
    }
  }
  return $errors;
}
function insertBiblio($biblio) {
  require_once("../classes/BiblioQuery.php");
  
  $biblioQ = new BiblioQuery();
  $biblioQ->connect();
  if ($biblioQ->errorOccurred()) {
    $biblioQ->close();
    displayErrorPage($biblioQ);
  }
  $bibid = $biblioQ->insert($biblio);
  if (!$bibid) {
    $biblioQ->close();
    displayErrorPage($biblioQ);
  }
  $biblioQ->close();
  return $bibid;
}
function showForm($postVars, $pageErrors=[]) {
  global $tab, $nav, $loc;
  $helpPage = "biblioEdit";
  $focus_form_name = "newbiblioform";
  $focus_form_field = "materialCd";
  require_once("../shared/header.php");

  $cancelLocation = "../catalog/index.php";
  $headerWording=$loc->getText("biblioNewFormLabel");
?>
  <script language="JavaScript">
    <!--
      function matCdReload(){
        document.newbiblioform.posted.value='media_change';
        document.newbiblioform.submit();
      }
    //-->
  </script>
  <form name="newbiblioform" method="POST" action="../catalog/biblio_new.php">
<?php
  include("../catalog/biblio_fields.php");
  include("../shared/footer.php");
}

?>
