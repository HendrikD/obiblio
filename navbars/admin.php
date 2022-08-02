<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */
 
  require_once("../classes/Localize.php");
  $navLoc = new Localize(OBIB_LOCALE,"navbars");

?>
<input type="button" onClick="self.location='../shared/logout.php'" value="<?php echo $navLoc->getText("logout");?>" class="navbutton"><br />
<br />

<?php
function menuItem($nav, $navLoc, $thisNav, $textKey, $link, $needsAdminAuth=true){
  if(!$needsAdminAuth || $_SESSION["hasAdminAuth"]){
    $text= $navLoc->getText($textKey);
    if($nav == $thisNav){
      echo " &raquo; $text<br />";
    }
    else{
      echo "<a href=\"$link\" class=\"alt1\">$text</a><br />";
    }
  }
}

$data = [
  ["summary", "adminSummary", "../admin/index.php", false],
  ["staff", "adminStaff", "../admin/staff_list.php", false],
  ["settings", "adminSettings", "../admin/settings_edit_form.php?reset=Y"],
  ["classifications", "Member Types", "../admin/mbr_classify_list.php"],
  ["member_fields", "Member Fields", "../admin/member_fields_list.php"],
  ["materials", "adminMaterialTypes", "../admin/materials_list.php"],
  ["collections", "adminCollections", "../admin/collections_list.php"],
  ["checkout_privs", "Checkout Privs", "../admin/checkout_privs_list.php"],
  ["themes", "adminThemes", "../admin/theme_list.php"],
  ["themes", "adminThemes", "../admin/theme_list.php"],
];

foreach($data as $r){
  menuItem($nav, $navLoc, ...$r);
}


?>

<!--
< ?php if ($nav == "translation") { ?>
 &raquo; < ?php echo $navLoc->getText("adminTranslation");?><br>
< ?php } else { ?>
 <a href="../admin/translation_list.php" class="alt1">< ?php echo $navLoc->getText("adminTranslation");?></a><br>
< ?php } ?>

-->

<a href="javascript:popSecondary('../shared/help.php<?php if (isset($helpPage)) echo "?page=".H(addslashes((string) U($helpPage))); ?>')"><?php echo $navLoc->getText("help");?></a>

