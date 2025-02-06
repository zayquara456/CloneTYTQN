<?php
ob_start();
# Chỉnh charset
header("Content-Type: text/html; charset=UTF-8");
# Nhận dữ liệu từ url thẻ trễ
	$TxtType    = mysql_escape_string($_GET['TxtType']);
	$TxtTransId = mysql_escape_string($_GET['TxtTransId']);
	$TxtMenhGia = mysql_escape_string($_GET['TxtMenhGia']);
	$TxtKey 	= mysql_escape_string($_GET['TxtKey']);
	
$ip = @$_SERVER['REMOTE_ADDR'];
	//$key= md5('123.30.243.69');
///header("Location: index.php?f=napthe&do=thetre&TxtType=".$TxtType."&TxtTransId=".$TxtTransId."&TxtMenhGia=".$TxtMenhGia."&TxtKey=".$TxtKey."&key=".$key."");
//die("index.php?f=napthe&do=thetre&TxtType=".$TxtType."&TxtTransId=".$TxtTransId."&TxtMenhGia=".$TxtMenhGia."&TxtKey=".$TxtKey."&key=".md5('123.30.243.69')."");
if ($ip != '103.28.38.122' ){
	echo $ip. ': IP không hỗ trợ';
	exit;
}
elseif (($ip == '103.28.38.122' ) && (!empty($TxtType)) && (!empty($TxtTransId)) && (!empty($TxtMenhGia)))
{
	$key= md5('103.28.38.122');
	header("Location: index.php?f=napthe&do=thetre&TxtType=".$TxtType."&TxtTransId=".$TxtTransId."&TxtMenhGia=".$TxtMenhGia."&TxtKey=".$TxtKey."&key=".$key."");
	
}
ob_flush(); 
?>