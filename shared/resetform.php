<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */
 
  require_once("../shared/common.php");

  $temp_return_page = "";
  if (isset($_GET["RET"])){
    if (in_array($_GET["RET"], $pages, true)) {
      $_SESSION["returnPage"] = $_GET["RET"];
    } else {
      $_SESSION["returnPage"] = '../home/index.php';
    }
  }

  $tab = "home";
  $nav = "";

  require_once("../shared/get_form_vars.php");
  require_once("../shared/header.php");
  require_once("../classes/Localize.php");
  $loc = new Localize(OBIB_LOCALE,"shared");

?>

<br>
<center>
<form name="resetform" method="POST" action="resetform_do.php">
<table class="primary">
  <tr>
    <th><?php echo $loc->getText("resetFormMailTbleHdr"); ?>:</th>
  </tr>
  <tr>
    <td valign="top" class="primary" align="left">
<table class="primary">
  <tr>
    <td valign="top" class="noborder">
      <?php echo $loc->getText("resetFormMail"); ?>:</font>
    </td>
    <td valign="top" class="noborder">
      <input type="text" name="mail" size="20" maxlength="200"
      value="<?php if (isset($postVars["mail"])) echo H($postVars["mail"]); ?>" >
      <font class="error"><?php if (isset($pageErrors["mail"])) echo H($pageErrors["mail"]); ?></font>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center" class="noborder">
      <input type="submit" value="<?php echo $loc->getText("resetFormReset"); ?>" class="button">
    </td>

  </tr>
</table>
    </td>
  </tr>

</table>

</form>
</center>

<?php include("../shared/footer.php"); ?>
