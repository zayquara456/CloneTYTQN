<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$id = intval($_GET['id']);
$load_hf = isset($_GET['load_hf']) ? 1 : 0;
$type = isset($_GET['type']);
$table = "{$prefix}_document";
$query = "SELECT d.fattach, d.fattach_intro, d.images, u.folder FROM $table AS d, ".$prefix."_user AS u  WHERE d.user_id=u.id AND d.id=$id";

$resultinfo = $db->sql_query("SELECT n.id, c.title, c.catid, n.title FROM {$prefix}_news_cat AS c, {$prefix}_news AS n WHERE c.catid=n.catid AND id='$id'");
if ($db->sql_numrows($resultinfo) > 0) {
	list($id, $cattitle, $catid, $title) = $db->sql_fetchrow($resultinfo);
}

$result = $db->sql_query($query);
if($db->sql_numrows($result) != 1) {
	include("modules/".$adm_modname."/index.php");
	die();
} else {
	list($fattach, $fattach_intro, $images, $folder) = $db->sql_fetchrow($result);
	$path_upload = "$path_upload/$adm_modname/$folder";	
	
		
	@unlink(RPATH."$path_upload/$images");
	@unlink(RPATH."$path_upload/$fattach_intro");
	@unlink(RPATH."$path_upload/$fattach");
	$db->sql_query("DELETE FROM $table WHERE id=$id");
	fixcount_cat();
	//updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _DELETE_DOCUMENT);
	updateadmlog($admin_ar[0], _MODTITLE, 'Xóa tài liệu', 'Xóa tài liệu '.$title.' | ID-'.$id.' Thuộc chuyên mục '.$cattitle.' | ID-'.$catid);
	truncate_table(substr($table, strlen($prefix) + 1));
	echo "<script language=\"javascript\" type=\"text/javascript\">";
	echo "alert('Tài liệu xóa thành công!');";
	echo " window.location.href=\"".url_sid('index.php?f=document')."\";";
	echo "</script>";
}
?>