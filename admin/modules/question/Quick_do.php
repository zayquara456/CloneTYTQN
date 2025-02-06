<?php
if(!defined('CMS_ADMIN')) {
	die();
}

$fc = intval($_POST['fc']);
$id = $_POST['id'];

if ($fc == 1) {
	for($i =0; $i < sizeof($id); $i ++) {
		$db->sql_query("DELETE FROM ".$prefix."_question WHERE id='".intval($id[$i])."'");
	}	
}	

if ($fc == 2) {
	for($i =0; $i < sizeof($id); $i ++) {
		$db->sql_query("UPDATE ".$prefix."_question SET active='0' WHERE id='".intval($id[$i])."'");
	}	
}	

if ($fc == 3) {
	for($i =0; $i < sizeof($id); $i ++) {
		$db->sql_query("UPDATE ".$prefix."_question SET active='1' WHERE id='".intval($id[$i])."'");
	}	
}

updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, "Xu ly nhanh tin tuc");
header("Location: modules.php?f=".$adm_modname."&do=question");

?>