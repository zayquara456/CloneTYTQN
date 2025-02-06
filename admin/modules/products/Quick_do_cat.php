<?php
if(!defined('CMS_ADMIN')) {
	die();
}

$fc = intval($_POST['fc']);
$catid = $_POST['catid'];
$poz = $_POST['poz'];

if ($fc == 2) {
	for($i =0; $i < sizeof($catid); $i ++) {
		$db->sql_query("UPDATE ".$prefix."_products_cat SET active='0' WHERE catid='$catid[$i]'");
	}	
}	

if ($fc == 3) {
	for($i =0; $i < sizeof($catid); $i ++) {
		$db->sql_query("UPDATE ".$prefix."_products_cat SET active='1' WHERE catid='$catid[$i]'");
	}	
}

if ($fc == 4) {
	foreach ($poz as $catidx => $weight) {
		$catidx = intval($catidx);
		$weight = intval($weight);
		$db->sql_query("UPDATE ".$prefix."_products_cat SET weight='$weight' WHERE catid='$catidx'");
	}	
	fixweight_cat();
}		

updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _QUICKDO_CAT);
header("Location: modules.php?f=".$adm_modname."&do=categories");

?>