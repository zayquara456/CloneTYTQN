<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$id = intval($_GET['id']);
$result = $db->sql_query("SELECT bnid FROM ".$prefix."_advertise WHERE id='$id'");
if(empty($id) || $db->sql_numrows($result) != 1) {
	header("Location: {$adm_modname}.php");
	exit;
}

list($bnid) = $db->sql_fetchrow($result);

$db->sql_query("DELETE FROM ".$prefix."_advertise WHERE id='$id'");
fixweight();

updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _DELETE);
truncate_table("advertise");
header("Location: modules.php?f=".$adm_modname."&do=viewadv&id=$bnid");
?>