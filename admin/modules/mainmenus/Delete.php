<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$mid = intval($_GET['mid']);
$menu_type= $_GET['menu_type'];
$load_hf = isset($_GET['load_hf']) ? 1 : 0;

$result = $db->sql_query("SELECT*FROM ".$prefix."_mainmenus WHERE mid='$mid'");
if(empty($mid) || $db->sql_numrows($result) != 1) {
	include("modules/".$adm_modname."/index.php");
	die();
}	

$db->sql_query("DELETE FROM ".$prefix."_mainmenus WHERE mid='$mid'");
truncate_table("mainmenus");
fixweight_mn();
header("Location: modules.php?f=$adm_modname&menu_type=$menu_type");

?>