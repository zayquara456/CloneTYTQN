<?php

define('SECURITY_CODE','acud.vn-353453Q@#$$#');
function makeHash($fileName)
{
	global $_SERVER;
	return md5($_SERVER['REMOTE_ADDR'].$fileName.date('d.m.Y,H:i').SECURITY_CODE);
}
function verifyHash($fileName,$hashCode)
{
	global $_SERVER;
	return $hashCode== makeHash($fileName);
}
# Chỉnh charset
header("Content-Type: text/html; charset=UTF-8");
# Cấu hình database
//$config_server = array(
//	'type' 		 => 'mysql', // Loại cơ sở dữ liệu mssql hoặc mysql
//	'server' 	 => 'localhost', // Địa chỉ cơ sở dữ liệu. Localhost hoặc 127.0.0.1
//	'username'   => 'root', // Tên đăng nhập vào cơ sở dữ liệu
//	'password'   => '111111', // Mật khẩu vào cơ sở dữ liệu
//	'database'   => 'acud2013', // Database sử dụng
//	'hosting'	 => true // Nếu sử dụng hosting điền true , sử dụng server điền false
//);
$config_server = array(
	'type' 		 => 'mysql', // Loại cơ sở dữ liệu mssql hoặc mysql
	'server' 	 => 'localhost', // Địa chỉ cơ sở dữ liệu. Localhost hoặc 127.0.0.1
	'username'   => 'news', // Tên đăng nhập vào cơ sở dữ liệu
	'password'   => 'WMOX9iMLLK3', // Mật khẩu vào cơ sở dữ liệu
	'database'   => 'news', // Database sử dụng
	'hosting'	 => true // Nếu sử dụng hosting điền true , sử dụng server điền false
);
# Cấu hình table chứa tiền , sử dụng cho các Game Private
$config_table = "adoosite_";

if($config_server['type'] == 'mysql')
{
	$link = @mysql_connect($config_server["server"], $config_server["username"], $config_server["password"]);
	if(!$link){die('Kết nối MySQL thất bại');}
	mysql_select_db($config_server["database"]);
}elseif($config_server['type'] == 'mssql')
{
	// tạo đối tượng database
	$db = &ADONewConnection('mssql'); 
	// kết nối cơ sở dữ liệu
	$connect_mssql = $db->Connect($config_server['server'],$config_server['username'],$config_server['password'],$config_server['database']); 
	if (!$connect_mssql){die("Lỗi , không thể kết nối tới SQL Server");}
}else{die('Yêu cầu thiết lập đúng <pre>$config_server[\'type\']</pre> là mssql hoặc mysql');}
	
		//$query_update = "UPDATE `".$config_money['Table']."` SET `".$config_money['FieldChuaTien']."` = `".$config_money['FieldChuaTien']."` + '".$TienDuocHuong."' WHERE `".$config_money['FieldUsername']."` = '".$TxtAccount."';";
	//mysql_query($query_update);
$file = $_GET['file'];

$result = mysql_query("SELECT n.id, u.folder, n.fattach, n.link_extend, n.code FROM adoosite_user AS u, adoosite_document AS n WHERE u.id=n.user_id AND n.code='$file'");

mysql_query("UPDATE adoosite_document SET hits_download=hits_download+1 WHERE code='$file'");

if($result){
$row = mysql_fetch_array($result);
	//cap nhat ma tai lieu
	
	
	//if(!empty($row['folder']) && !empty($row['fattach']))
	//{
		set_time_limit(0);
		//$code	= md5(rand(0,9).$row['id']);
		$code=makeHash($row['id']);
		mysql_query("UPDATE adoosite_document SET code=".$code." WHERE id=".$row['id']."");
		//output_file('document/'.$row['folder'].'/'.$row['fattach'], $row['fattach'], '');
		$link_extend= $row['link_extend'];
		header("Location: $link_extend");
	//}
	
}
{
	echo '
	<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Language" content="en-us">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>ACUD.VN: Chuyên Trang Kiến Trúc Quy Hoạch, Hạ tầng kỹ thuật- Thông báo từ hệ thống</title>
<link rel="StyleSheet" href="templates/Adoosite/css/styles.css" type="text/css">
</head>
<body bgcolor="#CCCCCC">

<table border="0" width="100%" cellpadding="0" style="border-collapse: collapse; margin-top: 150px">
<tr>
<td align="center">
<table border="1" bgcolor="#FFFFFF" cellpadding="5" style="border-collapse: collapse" width="65%" bordercolor="#035683">
<tr>
<td bgcolor="#1E84BC" background="images/blbg.gif" class="titlearl"><b><font color="#FFFFFF">Thông báo từ hệ thống....</font></b></td>
</tr>
<tr>
<td style="padding: 10px" class="titlearl" align="center">Tài liệu không tồn tại!</td></tr></table>
</td>
</tr>
</table>

</body>
</html>
	';
}
?>