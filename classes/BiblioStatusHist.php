<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */
 
  require_once("../classes/Localize.php");

/******************************************************************************
 * BiblioStatusHist represents a history of bilio checkin and checkout status changes
 *
 * @author David Stevens <dave@stevens.name>;
 * @version 1.0
 * @access public
 ******************************************************************************
 */
class BiblioStatusHist {
  public $_bibid = "";
  public $_copyid = "";
  public $_biblioBarcodeNmbr = "";
  public $_title = "";
  public $_author = "";
  public $_statusCd = "";
  public $_statusBeginDt = "";
  public $_mbrid = "";
  public $_lastName = "";
  public $_firstName = "";
  public $_mbrBarcodeNmbr = "";
  public $_dueBackDt = "";
  public $_renewalCount = "";

  /****************************************************************************
   * Getter methods for all fields
   * @return string
   * @access public
   ****************************************************************************
   */
  function getBibid() {
    return $this->_bibid;
  }
  function getCopyid() {
    return $this->_copyid;
  }
  function getBiblioBarcodeNmbr() {
    return $this->_biblioBarcodeNmbr;
  }
  function getTitle() {
    return $this->_title;
  }
  function getAuthor() {
    return $this->_author;
  }
  function getStatusCd() {
    return $this->_statusCd;
  }
  function getStatusBeginDt() {
    return $this->_statusBeginDt;
  }
  function getMbrid() {
    return $this->_mbrid;
  }
  function getLastName() {
    return $this->_lastName;
  }
  function getFirstName() {
    return $this->_firstName;
  }
  function getMbrBarcodeNmbr() {
    return $this->_mbrBarcodeNmbr;
  }
  function getDueBackDt() {
    return $this->_dueBackDt;
  }
  function getRenewalCount() {
    return $this->_renewalCount;
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
  function setCopyid($value) {
    $this->_copyid = trim((string) $value);
  }
  function setBiblioBarcodeNmbr($value) {
    $this->_biblioBarcodeNmbr = trim((string) $value);
  }
  function setTitle($value) {
    $this->_title = trim((string) $value);
  }
  function setAuthor($value) {
    $this->_author = trim((string) $value);
  }
  function setStatusCd($value) {
    $this->_statusCd = trim((string) $value);
  }
  function setStatusBeginDt($value) {
    $this->_statusBeginDt = trim((string) $value);
  }
  function setMbrid($value) {
    $this->_mbrid = trim((string) $value);
  }
  function setLastName($value) {
    $this->_lastName = trim((string) $value);
  }
  function setFirstName($value) {
    $this->_firstName = trim((string) $value);
  }
  function setMbrBarcodeNmbr($value) {
    $this->_mbrBarecodeNmbr = trim((string) $value);
  }
  function setDueBackDt($value) {
    $this->_dueBackDt = trim((string) $value);
  }
  function setRenewalCount($value) {
    $this->_renewalCount = trim((string) $value);
  }
}

?>
