<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$catid = intval($_GET['catid']);
$stat = intval($_GET['stat']);
$load_hf = isset($_GET['load_hf']) ? 1 : 0;

$result = $db->sql_query("SELECT alanguage FROM ".$prefix."_products_cat WHERE catid='$catid'");
if(empty($catid) || $db->sql_numrows($result) != 1) {
	include("modules/".$adm_modname."/Categories.php");
	die();
}	

$db->sql_query("UPDATE ".$prefix."_products_cat SET active='$stat' WHERE catid='$catid'");
//onfile_cat();
include("modules/".$adm_modname."/Categories.php");

?>