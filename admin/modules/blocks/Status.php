<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$bid = intval($_GET['id']);
$stat = intval($_GET['stat']);
$load_hf = isset($_GET['load_hf']) ? 1 : 0;

$result = $db->sql_query("SELECT blanguage FROM ".$prefix."_blocks WHERE bid='$bid'");
if(!empty($bid) && ($db->sql_numrows($result) == 1)) {
	$db->sql_query("UPDATE ".$prefix."_blocks SET active='$stat' WHERE bid='$bid'");
	blist();
	updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _STATUS);
}
include("modules/".$adm_modname."/index.php");
?>