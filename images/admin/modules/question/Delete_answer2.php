<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$id = intval($_GET['id']);
$catid=intval($_GET['catid']);
$load_hf = isset($_GET['load_hf']) ? 1 : 0;
	$table = "{$prefix}_answer";
	$db->sql_query("DELETE FROM $table WHERE id=$id");
	//updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _DELETE_CAMNANG);
	//truncate_table(substr($table, strlen($prefix) + 1));
	header("Location: modules.php?f=question&do=question_ans&id=$catid");

?>