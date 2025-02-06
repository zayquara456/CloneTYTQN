<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$mid = intval($_GET['mid']);
$load_hf = isset($_GET['load_hf']) ? 1 : 0;

$result = $db->sql_query("SELECT*FROM ".$prefix."_question WHERE id='$mid'");
if(empty($mid) || $db->sql_numrows($result) != 1) {
	include("modules/".$adm_modname."/index.php");
	die();
}	

$db->sql_query("DELETE FROM ".$prefix."_question WHERE id='$mid'");
truncate_table("question");
fixweight_mn();
header("Location: modules.php?f=$adm_modname");

?>