<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}
$newsid = intval($_GET['newsid']);
$tabid = intval($_GET['tabid']);
$load_hf = isset($_GET['load_hf']) ? 1 : 0;

$result = $db->sql_query("SELECT*FROM ".$prefix."_news_tab WHERE tabid='$tabid'");
if(empty($tabid) || $db->sql_numrows($result) != 1) {
	include("modules/".$adm_modname."/index.php");
	die();
}	

$db->sql_query("DELETE FROM ".$prefix."_news_tab WHERE tabid='$tabid'");
truncate_table("news_tab");
fixweight_newstab();
//include("modules/".$adm_modname."/index.php");
header("Location: modules.php?f=".$adm_modname."&do=tabnews&newsid=$newsid");

?>