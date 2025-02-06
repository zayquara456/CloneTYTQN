<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$id = intval($_GET['id']);
$load_hf = isset($_GET['load_hf']) ? 1 : 0;
$type = isset($_GET['type']);
$table = "{$prefix}_link_report";
$db->sql_query("DELETE FROM $table WHERE id=$id");
updateadmlog($admin_ar[0], _MODTITLE, 'Xóa báo link hỏng', 'Xóa báo link hỏng | ID-'.$id.'');
	echo "<script language=\"javascript\" type=\"text/javascript\">";
	echo "alert('Tài liệu xóa thành công!');";
	echo " window.location.href=\"".url_sid('modules.php?f=document&do=link_report')."\";";
	echo "</script>";
?>