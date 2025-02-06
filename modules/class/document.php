<?php
function output_file($file, $name, $mime_type=''){
    /*
    This function takes a path to a file to output ($file),
    the filename that the browser will see ($name) and
    the MIME type of the file ($mime_type, optional).
    
    If you want to do something on download abort/finish,
    register_shutdown_function('function_name');
    */
    if(!is_readable($file)) die('File not found or inaccessible!');
     
    $size = filesize($file);
    $name = rawurldecode($name);
     
    /* Figure out the MIME type (if not specified) */
    $known_mime_types=array(
       "pdf" => "application/pdf",
       "txt" => "text/plain",
       "html" => "text/html",
       "htm" => "text/html",
       "exe" => "application/octet-stream",
       "zip" => "application/zip",
	   "rar" => "application/x-rar-compressed",
       "doc" => "application/msword",
       "xls" => "application/vnd.ms-excel",
       "ppt" => "application/vnd.ms-powerpoint",
       "gif" => "image/gif",
       "png" => "image/png",
       "jpeg"=> "image/jpg",
       "jpg" =>  "image/jpg",
       "php" => "text/plain"
    );
          
    if($mime_type==''){
        $file_extension = strtolower(substr(strrchr($file,"."),1));
        if(array_key_exists($file_extension, $known_mime_types)){
           $mime_type=$known_mime_types[$file_extension];
        } else {
           $mime_type="application/force-download";
        };
    };
     
    @ob_end_clean(); //turn off output buffering to decrease cpu usage
     
    // required for IE, otherwise Content-Disposition may be ignored
    if(ini_get('zlib.output_compression'))
     ini_set('zlib.output_compression', 'Off');
      
    header('Content-Type: ' . $mime_type);
    header('Content-Disposition: attachment; filename="'.$name.'"');
    header("Content-Transfer-Encoding: binary");
    header('Accept-Ranges: bytes');
     
    /* The three lines below basically make the
       download non-cacheable */
    header("Cache-control: private");
    header('Pragma: private');
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    
    // multipart-download and download resuming support
    if(isset($_SERVER['HTTP_RANGE']))
    {
       list($a, $range) = explode("=",$_SERVER['HTTP_RANGE'],2);
       list($range) = explode(",",$range,2);
       list($range, $range_end) = explode("-", $range);
       $range=intval($range);
       if(!$range_end) {
           $range_end=$size-1;
       } else {
           $range_end=intval($range_end);
       }
    
       $new_length = $range_end-$range+1;
       header("HTTP/1.1 206 Partial Content");
       header("Content-Length: $new_length");
       header("Content-Range: bytes $range-$range_end/$size");
    } else {
       $new_length=$size;
       header("Content-Length: ".$size);
    }
    
    /* output the file itself */
    $chunksize = 1*(1024*1024); //you may want to change this
    $bytes_send = 0;
    if ($file = fopen($file, 'r'))
    {
       if(isset($_SERVER['HTTP_RANGE']))
       fseek($file, $range);
        
       while(!feof($file) &&
           (!connection_aborted()) &&
           ($bytes_send<$new_length)
             )
       {
           $buffer = fread($file, $chunksize);
           print($buffer); //echo($buffer); // is also possible
           flush();
           $bytes_send += strlen($buffer);
       }
    fclose($file);
    } else die('Error - can not open file.');
     
    die();
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

$result = mysql_query("SELECT n.id, u.folder, n.fattach, n.code FROM adoosite_user AS u, adoosite_document AS n WHERE u.id=n.user_id AND n.code='$file'");

mysql_query("UPDATE adoosite_document SET hits_download=hits_download+1 WHERE code='$file'");

if($result){
$row = mysql_fetch_array($result);
	//cap nhat ma tai lieu
	
	
	if(!empty($row['folder']) && !empty($row['fattach']))
	{
		set_time_limit(0);
		mysql_query("UPDATE adoosite_document SET code=MD5(RAND()) WHERE id=".$row['id']."");
		output_file('document/'.$row['folder'].'/'.$row['fattach'], $row['fattach'], '');
	}
	
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