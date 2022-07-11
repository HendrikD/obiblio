<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */
 
/******************************************************************************
 * UsmarcTagDm represents a row in usmarc_tag_dm.
 *
 * @author David Stevens <dave@stevens.name>;
 * @version 1.0
 * @access public
 ******************************************************************************
 */
class UsmarcTagDm {
  public $_blockNmbr = "";
  public $_tag = "";
  public $_description = "";
  public $_ind1Description = "";
  public $_ind2Description = "";
  public $_repeatableFlg = "";

  /****************************************************************************
   * Getter methods for all fields
   * @return string
   * @access public
   ****************************************************************************
   */
  function getBlockNmbr() {
    return $this->_blockNmbr;
  }
  function getTag() {
    return $this->_tag;
  }
  function getDescription() {
    return $this->_description;
  }
  function getInd1Description() {
    return $this->_ind1Description;
  }
  function getInd2Description() {
    return $this->_ind2Description;
  }
  function getRepeatableFlg() {
    return $this->_repeatableFlg;
  }

  /****************************************************************************
   * Setter methods for all fields
   * @param string $value new value to set
   * @return void
   * @access public
   ****************************************************************************
   */
  function setBlockNmbr($value) {
    if (trim((string) $value) == "") {
      $this->_blockNmbr = "0";
    } else {
      $this->_blockNmbr = trim((string) $value);
    }
  }
  function setTag($value) {
    if (trim((string) $value) == "") {
      $this->_tag = "0";
    } else {
      $this->_tag = trim((string) $value);
    }
  }
  function setDescription($value) {
    $this->_description = trim((string) $value);
  }
  function setInd1Description($value) {
    $this->_ind1Description = trim((string) $value);
  }
  function setInd2Description($value) {
    $this->_ind2Description = trim((string) $value);
  }
  function setRepeatableFlg($value) {
    $this->_repeatableFlg = trim((string) $value);
  }

}

?>
