<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$catid = intval($_GET['id']);
$stat = intval($_GET['stat']);

$result = $db->sql_query("SELECT alanguage FROM ".$prefix."_question_cat WHERE catid='$catid'");
if(empty($catid) || $db->sql_numrows($result) != 1) {
	header("Location: ".$adm_modname.".php");
	die();
}	

$db->sql_query("UPDATE ".$prefix."_question_cat SET active='$stat' WHERE catid='$catid'");
//onfile_cat();

include("modules/".$adm_modname."/Categories.php");
?>