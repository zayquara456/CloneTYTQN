<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$catid = intval($_GET['catid']);

$result = $db->sql_query("SELECT sub_id FROM ".$prefix."_thecao_cat WHERE catid='$catid'");
if(empty($catid) || $db->sql_numrows($result) != 1) {
	header("Location: modules.php?f=".$adm_modname."");
	die();
}

list($sub_id) = $db->sql_fetchrow($result);	

$db->sql_query("DELETE FROM ".$prefix."_thecao WHERE catid='$catid'");
$db->sql_query("DELETE FROM ".$prefix."_thecao_cat WHERE catid='$catid'");

truncate_table("thecao_cat");
truncate_table("thecao");
fixweight_cat();
fixsubcat();
updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _DELETE_CAT);
header("Location: modules.php?f=".$adm_modname."&do=categories");

?>