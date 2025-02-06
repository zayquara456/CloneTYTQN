<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$id = intval($_GET['id']);
$stat = intval($_GET['stat']);
$load_hf = isset($_GET['load_hf']) ? 1 : 0;

$result = $db->sql_query("SELECT* FROM ".$prefix."_advertise_scroll WHERE id='$id'");
if(empty($id) || $db->sql_numrows($result) != 1) {
	header("Location: modules.php?f=".$adm_modname."&do=scroll");
	exit;	
}
	
$db->sql_query("UPDATE ".$prefix."_advertise_scroll SET active='$stat' WHERE id='$id'");

include("modules/".$adm_modname."/Scroll.php");

?>