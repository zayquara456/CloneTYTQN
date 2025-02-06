<?php
if(!defined('CMS_ADMIN')) {
	die();
}

$fc = intval($_POST['fc']);
$id = $_POST['id'];
$catchange = intval($_POST['catchange']);


if ($fc == 1) {
	for($i =0; $i < sizeof($id); $i ++) {
		$db->sql_query("DELETE FROM ".$prefix."_news WHERE id='".intval($id[$i])."'");
	}	
}	

if ($fc == 2) {
	for($i =0; $i < sizeof($id); $i ++) {
		$db->sql_query("UPDATE ".$prefix."_news SET active='0' WHERE id='".intval($id[$i])."'");
	}	
}	

if ($fc == 3) {
	for($i =0; $i < sizeof($id); $i ++) {
		$db->sql_query("UPDATE ".$prefix."_news SET active='1' WHERE id='".intval($id[$i])."'");
	}	
}
if ($catchange!=0)
{
	for($i =0; $i < sizeof($id); $i ++) {
		$db->sql_query("UPDATE ".$prefix."_news SET catid='$catchange' WHERE id='".intval($id[$i])."'");
	}
	echo "<script language=\"javascript\" type=\"text/javascript\">";
		echo "alert('Chuyển chuyên mục thành công!');";
		echo " window.location.href=\"modules.php?f=".$adm_modname."\";";
	echo "</script>";
}
updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, "Xu ly nhanh tin tuc");
header("Location: modules.php?f=".$adm_modname."");

?>