<?php
if(!defined('CMS_ADMIN')) {
	die();
}

$v = intval($_POST['v']);
$id = $_POST['id'];
$poz = $_POST['poz'];

if($v == 1) {
	for($i =0; $i < sizeof($id); $i ++) {
		$db->sql_query("DELETE FROM ".$prefix."_blocks WHERE bid='$id[$i]'");
	}	
}	

if ($v == 2) {
	for($i =0; $i < sizeof($id); $i ++) {
		$db->sql_query("UPDATE ".$prefix."_blocks SET active='0' WHERE bid='$id[$i]'");
	}	
}	

if ($v == 3) {
	for($i =0; $i < sizeof($id); $i ++) {
		$db->sql_query("UPDATE ".$prefix."_blocks SET active='1' WHERE bid='$id[$i]'");
	}	
}

if ($v == 4) {
	foreach ($poz as $idx => $weight) {
		$idx = intval($idx);
		$weight = intval($weight);
		$db->sql_query("UPDATE ".$prefix."_blocks SET weight='$weight' WHERE bid='$idx'");
	}	
	fixweight();
}		

blist();
updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _QUICKDO);
header("Location: modules.php?f=$adm_modname");

?>