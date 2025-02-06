<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$id = intval($_GET['id']);
$load_hf = isset($_GET['load_hf']) ? 1 : 0;
	$table = "{$prefix}_question";
	$db->sql_query("DELETE FROM $table WHERE id=$id");
	$db->sql_query("DELETE FROM {$prefix}_answer WHERE qid=$id");
	updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _DELETE_CAMNANG);
	truncate_table(substr($table, strlen($prefix) + 1));
	include("modules/".$adm_modname."/Question.php");

?>