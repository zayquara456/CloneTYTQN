<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$newsid = intval($_GET['newsid']);
$tabid = intval($_GET['tabid']);
$stat = intval($_GET['stat']);
$load_hf = isset($_GET['load_hf']) ? 1 : 0;

$result = $db->sql_query("SELECT*FROM ".$prefix."_news_tab WHERE tabid='$tabid'");
if(empty($tabid) || $db->sql_numrows($result) != 1) {
	include("modules/".$adm_modname."/index.php?f=news&do=tabnews&newsid=$newsid");
	die();
}	

$db->sql_query("UPDATE ".$prefix."_news_tab SET active='$stat' WHERE tabid='$tabid'");
header("Location: modules.php?f=news&do=tabnews&newsid=$newsid");

?>