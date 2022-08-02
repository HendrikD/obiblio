<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */
 
  require_once("../shared/common.php");

  if (!isset($_POST["mail"])){
    header("Location: loginform.php");
    exit();
  }

  $tab = "home";
  $nav = "";

  require_once("../shared/get_form_vars.php");
  require_once("../shared/header.php");
  require_once("../classes/Localize.php");
  $loc = new Localize(OBIB_LOCALE,"shared");

  mail( $_POST["mail"], "Spielothek Darmstadt Passwort Zurücksetzen", "Hier kannst du dein Passwort zurücksetzen.");

?>

<br>
<center>
<form name="loginform" method="POST" action="resetform_do.php">
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
      <input type="text" name="username" size="20" maxlength="20"
      value="<?php if (isset($postVars["username"])) echo H($postVars["username"]); ?>" >
      <font class="error"><?php if (isset($pageErrors["username"])) echo H($pageErrors["username"]); ?></font>
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
