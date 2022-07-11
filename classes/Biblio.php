<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */
 
  require_once("../classes/Localize.php");

/******************************************************************************
 * Biblio represents a library bibliography record.  Contains business rules for
 * bibliography data validation.
 *
 * @author David Stevens <dave@stevens.name>;
 * @version 1.0
 * @access public
 ******************************************************************************
 */
class Biblio {
  public $_bibid = "";
  public $_createDt = "";
  public $_lastChangeDt = "";
  public $_lastChangeUserid = "";
  public $_lastChangeUsername = "";
  public $_materialCd = "";
  public $_collectionCd = "";
  public $_callNmbr1 = "";
  public $_callNmbr2 = "";
  public $_callNmbr3 = "";
  public $_callNmbrError = "";
  public $_biblioFields = [];
  public $_opacFlg = true;
  public $_loc;

  function __construct () {
    $this->_loc = new Localize(OBIB_LOCALE,"classes");
  }

  /****************************************************************************
   * @return boolean true if data is valid, otherwise false.
   * @access public
   ****************************************************************************
   */
  function validateData() {
    $valid = true;
    if ($this->_callNmbr1 == "") {
      $valid = false;
      $this->_callNmbrError = $this->_loc->getText("biblioError1");
    }
    foreach ($this->_biblioFields as $key => $value) {
      if (!$this->_biblioFields[$key]->validateData()) {
        $valid = false;
      }
    }
    return $valid;
  }

  /****************************************************************************
   * Getter methods for all fields
   * @return string
   * @access public
   ****************************************************************************
   */
  function getBibid() {
    return $this->_bibid;
  }
  function getCreateDt() {
    return $this->_createDt;
  }
  function getLastChangeDt() {
    return $this->_lastChangeDt;
  }
  function getLastChangeUserid() {
    return $this->_lastChangeUserid;
  }
  function getLastChangeUsername() {
    return $this->_lastChangeUsername;
  }
  function getMaterialCd() {
    return $this->_materialCd;
  }
  function getCollectionCd() {
    return $this->_collectionCd;
  }
  function getCallNmbr1() {
    return $this->_callNmbr1;
  }
  function getCallNmbr2() {
    return $this->_callNmbr2;
  }
  function getCallNmbr3() {
    return $this->_callNmbr3;
  }
  function getCallNmbrError() {
    return $this->_callNmbrError;
  }
  function getBiblioFields() {
    return $this->_biblioFields;
  }
  function showInOpac() {
    return $this->_opacFlg;
  }

  /****************************************************************************
   * Setter methods for all fields
   * @param string $value new value to set
   * @return void
   * @access public
   ****************************************************************************
   */
  function setBibid($value) {
    $this->_bibid = trim((string) $value);
  }
  function setCreateDt($value) {
    $this->_createDt = trim((string) $value);
  }
  function setLastChangeDt($value) {
    $this->_lastChangeDt = trim((string) $value);
  }
  function setLastChangeUserid($value) {
    $this->_lastChangeUserid = trim((string) $value);
  }
  function setLastChangeUsername($value) {
    $this->_lastChangeUsername = trim((string) $value);
  }
  function setMaterialCd($value) {
    $this->_materialCd = trim((string) $value);
  }
  function setCollectionCd($value) {
    $this->_collectionCd = trim((string) $value);
  }
  function setCallNmbr1($value) {
    $this->_callNmbr1 = trim((string) $value);
  }
  function setCallNmbr2($value) {
    $this->_callNmbr2 = trim((string) $value);
  }
  function setCallNmbr3($value) {
    $this->_callNmbr3 = trim((string) $value);
  }
  function setCallNmbrError($value) {
    $this->_callNmbrError = trim((string) $value);
  }
  function setOpacFlg($flag) {
    if ($flag == true) {
      $this->_opacFlg = true;
    } else {
      $this->_opacFlg = false;
    }
  }
  function addBiblioField($index, $value) {
    $keySuffix = "";
    while (array_key_exists($index.$keySuffix, $this->_biblioFields)) {
      if ($keySuffix == "") {
        $keySuffix = 1;
      } else {
        $keySuffix = $keySuffix + 1;
      }
    }    
    $this->_biblioFields[$index.$keySuffix] = $value;
  }
}

?>
