<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");
///////////////////////
//	xoa luot tai tai lieu, tru tien tai khoan nguoi dang, cong tien tai khoan nguoi tai
//	ghi log xoa luot tai, tai khoan nguoi dang, tai khoan nguoi tai
////////////////////////////
$id = intval($_GET['id']);
$table = "{$prefix}_document_order";
$query = "SELECT o.id, o.user_buy, o.user_sale, o.documentid, o.price, o.time, d.title FROM $table as o, {$prefix}_document as d WHERE o.documentid=d.id AND o.id=$id";
$result = $db->sql_query($query);
if($db->sql_numrows($result) != 1) {
	include("modules/".$adm_modname."/index.php");
	die();
} else {
	list($id, $user_buy, $user_sale, $documentid, $price, $time, $title) = $db->sql_fetchrow($result);
	//tru tien tai khoan nguoi dang
	list ($money_sale) = $db->sql_fetchrow($db->sql_query("SELECT money FROM ".$prefix."_user WHERE id=$user_sale"));
	list ($money_buy) = $db->sql_fetchrow($db->sql_query("SELECT money FROM ".$prefix."_user WHERE id=$user_buy"));
	$db->sql_query("UPDATE ".$prefix."_user SET money=money-'$price' WHERE id='$user_sale'");
	//ghi log tai khoan nguoi tai
	updateuserlog($user_sale,'Hủy lượt tải',$money_sale,$price,$money_sale-$price,'-',$title);
	//cong tien tai khoang nguoi tai
	$db->sql_query("UPDATE ".$prefix."_user SET money=money+'$price' WHERE id='$user_buy'");
	updateuserlog($user_buy,'Hủy lượt tải',$money_buy,$price,$money_buy+$price,'+',$title);
	//xoa tai lieu khoi doc order
	$db->sql_query("DELETE FROM {$prefix}_document_order WHERE id=$id");
	//updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _DELETE_DOCUMENT);
	updateadmlog($admin_ar[0], "Tài liệu", 'Xóa lượt tải tài liệu', 'Xóa lượt tài liệu '.$title);
	truncate_table(substr($table, strlen($prefix) + 1));
	//echo "xoa thanh cong";
	echo "<script language=\"javascript\" type=\"text/javascript\">";
	echo "alert('Lượt tải tài liệu xóa thành công!');";
	echo " window.location.href=\"modules.php?f=dashboard&do=static_user_epdetail&user_sale='.$user_sale.'\";";
	echo "</script>";
}
?>