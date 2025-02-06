<?php
if(!defined('CMS_ADMIN')) die();

$v = intval($_POST['v']);
$id = $_POST['bnid'];

if ($v == 1) {
	for($i = 0; $i < sizeof($id); $i ++) {
		$result_adv = $db->sql_query("SELECT images FROM {$prefix}_advertise WHERE bnid=".intval($id[$i]));
		while(list($images) = $db->fetchrow($result_adv)) {
			@unlink("../$path_upload/$images");
		}	
		$db->sql_query("DELETE FROM {$prefix}_advertise WHERE bnid=".intval($id[$i]));
		$db->sql_query("DELETE FROM {$prefix}_advertise_banners WHERE bnid=".intval($id[$i]));
	}	
}	

if ($v == 2) {
	for($i = 0; $i < sizeof($id); $i ++) {
		$db->sql_query("UPDATE {$prefix}_advertise_banners SET active=0 WHERE bnid=".intval($id[$i]));
	}	
}	

if ($v == 3) {
	for($i = 0; $i < sizeof($id); $i ++) {
		$db->sql_query("UPDATE {$prefix}_advertise_banners SET active=1 WHERE bnid=".intval($id[$i]));
	}	
}

updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _QUICKDO);

header("Location: modules.php?f=".$adm_modname."&do=banners");

?>