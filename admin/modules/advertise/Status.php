<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$id = intval($_GET['id']);
$stat = intval($_GET['stat']);
$result = $db->sql_query("SELECT bnid FROM ".$prefix."_advertise WHERE id='$id'");
if(empty($id) || $db->sql_numrows($result) != 1) {
	header("Location: ".$adm_modname.".php");
	exit;	
}

list($bnid) = $db->sql_fetchrow($result);
	
$db->sql_query("UPDATE ".$prefix."_advertise SET active='$stat' WHERE id='$id'");

updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _STATUS);

header("Location: modules.php?f=".$adm_modname."&do=viewadv&id=$bnid");

?>