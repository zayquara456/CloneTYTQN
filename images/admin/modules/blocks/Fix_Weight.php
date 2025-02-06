<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$bpoz = $_POST['bpoz'];

foreach ($bpoz as $idx => $weight) {
	$idx = intval($idx);
	$weight = intval($weight);
	$db->sql_query("UPDATE ".$prefix."_blocks SET weight='$weight' WHERE bid='$idx'");
}	

fixweight();
blist();

updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _FIXWEIGHT_BLOCKS);

header("Location: ".$adm_modname.".php");

?>