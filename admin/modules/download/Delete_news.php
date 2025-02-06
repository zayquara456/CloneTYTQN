<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$id = intval($_GET['id']);
$load_hf = isset($_GET['load_hf']) ? 1 : 0;
$type = isset($_GET['type']);
	$table = "{$prefix}_download";
	$query = "SELECT fattach, images FROM $table WHERE id=$id";


$result = $db->sql_query($query);
if($db->sql_numrows($result) != 1) {
	include("modules/".$adm_modname."/index.php");
	die();
} else {
	list($fattach, $images) = $db->sql_fetchrow($result);
	//$get_path = get_path($time);
	$path_upload_img = "$path_upload/download";
		
	@unlink(RPATH."$path_upload_img/attachs/$fattach");
	$db->sql_query("DELETE FROM $table WHERE id=$id");
	fixcount_cat();
	updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _DELETE_NEWS);
	truncate_table(substr($table, strlen($prefix) + 1));
	include("modules/".$adm_modname."/index.php");
}
?>