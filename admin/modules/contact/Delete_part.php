<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$id = intval($_GET['id']);
$load_hf = isset($_GET['load_hf']) ? 1 : 0;

$db->sql_query("DELETE FROM ".$prefix."_contact_part WHERE id='$id'");
updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, ""._DELETE." "._CTPART."");
include("modules/".$adm_modname."/Part.php");

?>