<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$id = intval($_GET['id']);
$result = $db->sql_query("SELECT images FROM ".$prefix."_advertise_scroll WHERE id='$id'");
if(empty($id) || $db->sql_numrows($result) != 1) {
	header("Location: modules.php?f=".$adm_modname."&do=scroll");
	exit;	
}

list($images) = $db->sql_fetchrow($result);

@unlink("../$path_upload/adv/$images");

$db->sql_query("DELETE FROM ".$prefix."_advertise_scroll WHERE id='$id'");
fixweight_scroll();

header("Location: modules.php?f=".$adm_modname."&do=scroll");

?>