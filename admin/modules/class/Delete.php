<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$id = intval($_GET['id']);
$load_hf = isset($_GET['load_hf']) ? 1 : 0;
$type = isset($_GET['type']);
$table = "{$prefix}_document";
$query = "SELECT images FROM $table  WHERE id=$id";
$result = $db->sql_query($query);
if($db->sql_numrows($result) != 1) {
	include("modules/".$adm_modname."/index.php");
	die();
} else {
	$path_upload = "$path_upload/$adm_modname";	
	
		
	@unlink(RPATH."$path_upload/$images");
	$db->sql_query("DELETE FROM $table WHERE id=$id");
	//updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _DELETE_DOCUMENT);
	updateadmlog($admin_ar[0], _MODTITLE, 'Xóa lớp học', 'Xóa lớp học '.$title);
	echo "<script language=\"javascript\" type=\"text/javascript\">";
	echo "alert('LỚp học xóa thành công!');";
	echo " window.location.href=\"".url_sid('index.php?f='.$adm_modname.'')."\";";
	echo "</script>";
}
?>