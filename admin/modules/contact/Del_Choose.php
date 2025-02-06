<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$id = $_POST['id'];

for($i =0; $i < sizeof($id); $i ++) {
	$db->sql_query("DELETE FROM ".$prefix."_contact WHERE id='$id[$i]'");
}	
updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _DELETE);
header("Location: ".$adm_modname.".php");

?>