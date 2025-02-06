<?php
if(!defined('CMS_ADMIN')) {
	die();
}

$v = intval($_POST['v']);
$id = $_POST['id'];
$bnid = intval($_POST['bnid']);
$poz = $_POST['poz'];

if ($v == 1) {
	for($i =0; $i < sizeof($id); $i ++) {
		$db->sql_query("DELETE FROM ".$prefix."_advertise WHERE id='".intval($id[$i])."'");
	}	
}	

if ($v == 2) {
	for($i =0; $i < sizeof($id); $i ++) {
		$db->sql_query("UPDATE ".$prefix."_advertise SET active='0' WHERE id='".intval($id[$i])."'");
	}	
}	

if ($v == 3) {
	for($i =0; $i < sizeof($id); $i ++) {
		$db->sql_query("UPDATE ".$prefix."_advertise SET active='1' WHERE id='".intval($id[$i])."'");
	}	
}

if ($v == 4) {
	foreach ($poz as $idx => $weight) {
		$idx = intval($idx);
		$weight = intval($weight);
		$db->sql_query("UPDATE ".$prefix."_advertise SET weight='$weight' WHERE id='$idx'");
	}	
	fixweight();
}
fixcountbn();		
updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _QUICKDO);
header("Location: modules.php?f=".$adm_modname."&do=viewadv&id=$bnid");

?>