<?php

if(!defined('CMS_ADMIN')) {
	die();
}

$id = intval($_GET['id']);
$load_hf = isset($_GET['load_hf']) ? 1 : 0;

$db->sql_query("DELETE FROM ".$prefix."_admin_log WHERE id='$id'");

include("modules/".$adm_modname."/index.php");

?>