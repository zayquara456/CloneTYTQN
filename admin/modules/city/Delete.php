<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$id = intval($_GET['id']);
$db->sql_query("DELETE FROM ".$prefix."_city WHERE id='$id'");
$db->sql_query("DELETE FROM ".$prefix."_city_counts WHERE city_id='$id'");
fixweight_city();
updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _DELETE);
header("Location: modules.php?f=".$adm_modname."");

?>