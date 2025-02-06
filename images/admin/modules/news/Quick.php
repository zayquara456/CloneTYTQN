<?php
if(!defined('CMS_ADMIN')) {
	die();
}
$newsid = intval($_GET['newsid']);
$v = intval($_POST['v']);
$tabid = $_POST['tabid'];
$poz = $_POST['poz'];

if ($v == 1) {
	for($i =0; $i < sizeof($tabid); $i ++) {
		$db->sql_query("UPDATE ".$prefix."_news_tab SET active='0' WHERE tabid='$tabid[$i]'");
	}	
}	

if ($v == 2) {
	for($i =0; $i < sizeof($tabid); $i ++) {
		$db->sql_query("UPDATE ".$prefix."_news_tab SET active='1' WHERE tabid='$tabid[$i]'");
	}	
}

if ($v == 3) {
	foreach ($poz as $tabidx => $weight) {
		$tabidx = intval($tabidx);
		$weight = intval($weight);
		$db->sql_query("UPDATE ".$prefix."_news_tab SET weight='$weight' WHERE tabid='$tabidx'");
	}
	fixweight_newstab();
}
if ($v == 4) {
	for($i =0; $i < sizeof($tabid); $i ++) {
		//xoa menu con theo menu
		//$db->sql_query("DELETE FROM ".$prefix."_news_tab WHERE parentid='$tabid[$i]'");
		//xoa menu
		$db->sql_query("DELETE FROM ".$prefix."_news_tab WHERE tabid='$tabid[$i]'");
	}	
	truncate_table("news_tab");
	fixweight_newstab();
}

header("Location: modules.php?f=".$adm_modname."&do=tabnews&newsid=$newsid");

?>