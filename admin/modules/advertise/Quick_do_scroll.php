<?php
if(!defined('CMS_ADMIN')) {
	die();
}

$fc = intval($_POST['fc']);
$id = $_POST['id'];
$poz = $_POST['poz'];

if ($fc == 1) {
	for($i =0; $i < sizeof($id); $i ++) {
		list($images) = $db->sql_fetchrow($db->sql_query("SELECT images FROM ".$prefix."_advertise_scroll WHERE id='".intval($id[$i])."'"));
		@unlink("../$path_upload/$images");
		$db->sql_query("DELETE FROM ".$prefix."_advertise_scroll WHERE id='".intval($id[$i])."'");
	}	
}	

if ($fc == 2) {
	for($i =0; $i < sizeof($id); $i ++) {
		$db->sql_query("UPDATE ".$prefix."_advertise_scroll SET active='0' WHERE id='".intval($id[$i])."'");
	}	
}	

if ($fc == 3) {
	for($i =0; $i < sizeof($id); $i ++) {
		$db->sql_query("UPDATE ".$prefix."_advertise_scroll SET active='1' WHERE id='".intval($id[$i])."'");
	}	
}

if ($fc == 4) {
	foreach ($poz as $idx => $weight) {
		$idx = intval($idx);
		$weight = intval($weight);
		$db->sql_query("UPDATE ".$prefix."_advertise_scroll SET weight='$weight' WHERE id='$idx'");
	}	
	fixweight_scroll();
}

header("Location: modules.php?f=$adm_modname&do=scroll");

?>