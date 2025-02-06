<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$id = intval($_GET['id']);
$load_hf = isset($_GET['load_hf']) ? 1 : 0;

$result = $db->sql_query("SELECT sitename FROM ".$prefix."_rss WHERE rid='$id'");
if(empty($id) || $db->sql_numrows($result) != 1) {
	include("modules/".$adm_modname."/Setup_Rss.php");
	die();
} else {	
	$db->sql_query("DELETE FROM ".$prefix."_rss WHERE rid='$id'");
	updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _DELETE);
	include("modules/".$adm_modname."/Setup_Rss.php");
}

?>