<?php
if(!defined('CMS_ADMIN')) {
	die();
}

$v = intval($_POST['v']);
$id = $_POST['id'];

if ($v == 1) {
	for($i =0; $i < sizeof($id); $i ++) {
		$db->sql_query("DELETE FROM ".$prefix."_contact WHERE id='".intval($id[$i])."'");
	}	
}	

if ($v == 2) {
	for($i =0; $i < sizeof($id); $i ++) {
		$db->sql_query("UPDATE ".$prefix."_contact SET status='0' WHERE id='".intval($id[$i])."'");
	}	
}	

if ($v == 3) {
	for($i =0; $i < sizeof($id); $i ++) {
		$db->sql_query("UPDATE ".$prefix."_contact SET status='1' WHERE id='".intval($id[$i])."'");
	}	
}

if ($v == 4) {
	for($i =0; $i < sizeof($id); $i ++) {
		$db->sql_query("UPDATE ".$prefix."_contact SET status='2' WHERE id='".intval($id[$i])."'");
	}	
}

updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _QUICKDO);

header("Location: modules.php?f=".$adm_modname."");

?>