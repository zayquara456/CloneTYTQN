<?php
if(!defined('CMS_ADMIN')) {
	die();
}

$v = intval($_POST['v']);
$mid = $_POST['mid'];
$poz = $_POST['poz'];

if ($v == 1) {
	for($i =0; $i < sizeof($mid); $i ++) {
		$db->sql_query("UPDATE ".$prefix."_question SET active='0' WHERE id='$mid[$i]'");
	}	
}	

if ($v == 2) {
	for($i =0; $i < sizeof($mid); $i ++) {
		$db->sql_query("UPDATE ".$prefix."_question SET active='1' WHERE id='$mid[$i]'");
	}	
}

if ($v == 3) {
	foreach ($poz as $midx => $weight) {
		$midx = intval($midx);
		$weight = intval($weight);
		$db->sql_query("UPDATE ".$prefix."_question SET weight='$weight' WHERE id='$midx'");
	}
	fixweight_mn();
}
if ($v == 4) {
	for($i =0; $i < sizeof($mid); $i ++) {
		//xoa menu
		$db->sql_query("DELETE FROM ".$prefix."_question WHERE id='$mid[$i]'");
	}	
	truncate_table("question");
	fixweight_mn();
}

header("Location: modules.php?f=".$adm_modname."");

?>