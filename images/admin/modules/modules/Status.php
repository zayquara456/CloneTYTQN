<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$id = intval($_GET['id']);
$stat = intval($_GET['stat']);
$load_hf = isset($_GET['load_hf']) ? 1 : 0;

$result = $db->sql_query("SELECT title FROM ".$prefix."_modules WHERE mid='$id'");
if(empty($id) || $db->sql_numrows($result) != 1) {
	include("modules/".$adm_modname."/index.php");
}
else {	
	$db->sql_query("UPDATE ".$prefix."_modules SET active='$stat' WHERE mid='$id'");
	updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, ""._STATUS."");
	include("modules/".$adm_modname."/index.php");
}

?>