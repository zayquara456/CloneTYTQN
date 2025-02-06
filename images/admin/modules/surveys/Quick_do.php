<?php
if(!defined('CMS_ADMIN')) {
	die();
}

$v = intval($_POST['v']);
$id = $_POST['id'];

if ($v == 1) {
	for($i =0; $i < sizeof($id); $i ++) {
		$db->sql_query("DELETE FROM ".$prefix."_survey WHERE id='".intval($id[$i])."'");
	}	
}	

if ($v == 2) {
	for($i =0; $i < sizeof($id); $i ++) {
		$db->sql_query("UPDATE ".$prefix."_survey SET active='0' WHERE id='".intval($id[$i])."'");
	}	
}	

if ($v == 3) {
	for($i =0; $i < sizeof($id); $i ++) {
		$db->sql_query("UPDATE ".$prefix."_survey SET active='1' WHERE id='".intval($id[$i])."'");
	}	
}

header("Location: modules.php?f=".$adm_modname."");

?>