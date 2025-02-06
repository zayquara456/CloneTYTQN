<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$id = intval($_GET['id']);
$load_hf = isset($_GET['load_hf']) ? 1 : 0;

$result = $db->sql_query("SELECT blockfile FROM ".$prefix."_blocks WHERE bid='$id'");
if(empty($id) || $db->sql_numrows($result) != 1) {
	include("modules/".$adm_modname."/index.php");
	die();
} else {
	list($blockfile) = $db->sql_fetchrow($result);
	$db->sql_query("DELETE FROM ".$prefix."_blocks WHERE bid='$id'");
	if($blockfile !="" && file_exists("../".DATAFOLD."/blocks/$blockfile")) {
		@unlink("../".DATAFOLD."/blocks/$blockfile");
	}
	fixweight();
	blist();
	updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _DELETE);
	include("modules/".$adm_modname."/index.php");
}

?>