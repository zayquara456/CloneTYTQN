<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$id = intval($_GET['id']);
$title = $escape_mysql_string($_GET['title']);
$load_hf = 1;

$db->sql_query("UPDATE ".$prefix."_news_cat SET title='$title' WHERE catid=$id");
updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _NEWS_EDIT_CAT_TITLE);
include_once("modules/".$adm_modname."/Categories.php");
?>