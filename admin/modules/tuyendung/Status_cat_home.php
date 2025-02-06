<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$catid = intval($_GET['id']);
$stat = intval($_GET['stat']);

$result = $db->sql_query("SELECT alanguage FROM ".$prefix."_{$adm_modname}_cat WHERE catid='$catid'");
if(empty($catid) || $db->sql_numrows($result) != 1) {
	header("Location: modules.php?f=".$adm_modname."");
	die();
}	

$db->sql_query("UPDATE ".$prefix."_{$adm_modname}_cat SET onhome='$stat' WHERE catid='$catid'");
updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, "Xu ly hien thi chu de trang chu");
	header("Location: modules.php?f=".$adm_modname."&do=categories");

?>