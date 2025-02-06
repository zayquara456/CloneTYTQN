<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$mid = intval($_GET['id']);
$stat = intval($_GET['stat']);
$load_hf = isset($_GET['load_hf']) ? 1 : 0;

$result = $db->sql_query("SELECT*FROM ".$prefix."_question WHERE id='$mid'");
if(empty($mid) || $db->sql_numrows($result) != 1) {
	//include("modules/".$adm_modname."/index.php");
	header("Location: modules.php?f=".$adm_modname."");
	die();
}	

$db->sql_query("UPDATE ".$prefix."_question SET active='$stat' WHERE id='$mid'");
//include("modules/".$adm_modname."/index.php");
header("Location: modules.php?f=".$adm_modname."");

?>