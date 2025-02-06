<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$id = intval($_GET['id']);
$result = $db->sql_query("SELECT title FROM {$prefix}_advertise_banners WHERE bnid=$id");
if(empty($id) || $db->sql_numrows($result) != 1) {
	header("Location: modules.php?f=$adm_modname&do=banners");
	exit;
}

$result_adv = $db->sql_query("SELECT images FROM {$prefix}_advertise WHERE bnid=$id");
if($db->sql_numrows($result_adv) > 0) {
	while(list($images) = $db->sql_fetchrow($result_adv)) {
		@unlink("../$path_upload/adv/$images");
	}		
}

$db->sql_query("DELETE FROM {$prefix}_advertise WHERE bnid=$id");
$db->sql_query("DELETE FROM {$prefix}_advertise_banners WHERE bnid=$id");

updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _DELETE);

header("Location: modules.php?f=$adm_modname&do=banners");
?>