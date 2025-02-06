<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$id = intval($_GET['id']);
$stat = intval($_GET['stat']);
$load_hf = 1;

$table = "{$prefix}_document";
$db->sql_query("SELECT catid FROM $table WHERE id=$id");
list($catid) = $db->sql_fetchrow();

if($stat == 0) {
	$db->sql_query("UPDATE $table SET nstart=0 WHERE id=$id");
	if ($_GET['type'] == 'normal') $db->sql_query("UPDATE {$prefix}_document_cat SET startid=0 WHERE catid=$catid AND startid=$id AND alanguage='$currentlang'");
} else if ($stat == 1) {
	$db->sql_query("UPDATE $table SET nstart=1 WHERE id=$id");
	$query = "UPDATE $table SET nstart=0 WHERE id!=$id AND alanguage='$currentlang'";
	if ($_GET['type'] == 'normal') $query .= " AND catid=$catid";
	$db->sql_query($query);
	if ($_GET['type'] == 'normal') $db->sql_query("UPDATE {$prefix}_document_cat SET startid=$id WHERE catid=$catid AND alanguage='$currentlang'");
}
updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _UPDATE_NEWS_START);
//if ($ajax_active == 0) {
	header("Location: modules.php?f=".$adm_modname);
//} else {
//	include_once("modules/$adm_modname/index.php");
//}
?>