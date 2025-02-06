<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$id = intval($_GET['id']);
$result = $db->sql_query("SELECT title, cityid FROM ".$prefix."_atm WHERE id='$id'");
list($title,$cityid) = $db->sql_fetchrow($result);
$db->sql_query("DELETE FROM ".$prefix."_atm WHERE id='$id'");
fixweight_atm();
updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _DELETE);

header("Location: modules.php?f=".$adm_modname."&id=$cityid");

?>