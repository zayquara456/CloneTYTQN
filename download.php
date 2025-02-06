<?php
session_start();
$client_ip = $_SERVER['HTTP_CLIENT_IP'];
if (!strstr($client_ip,".")) $client_ip = $_SERVER['REMOTE_ADDR'];
if (!strstr($client_ip,".")) $client_ip = getenv( "REMOTE_ADDR" );
$client_ip = trim($client_ip);
define('SECURITY_CODE','thuvienxaydung.net-353453Q@#$$#');
$url_site = 'http://'.$_SERVER['HTTP_HOST'].'/';
define("USER_SESS","nvu_fCQ82");
$ck_user = $_SESSION[USER_SESS];
$code = $_GET['u'];
$yep = isset($_GET["yep"]) ? $_GET["yep"] : 0;
$where="";
if($code!="")
	$where.="n.code='$code' AND ";
function makeHash($fileName)
{
	global $_SERVER;
	return md5($_SERVER['REMOTE_ADDR'].$fileName.date('d.m.Y,H:i').SECURITY_CODE);
}
function verifyHash($fileName,$hashCode)
{
	global $_SERVER;
	return $hashCode == makeHash($fileName);
}

function system_report($content,$redirect)
{
?>
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<title>Thông báo từ hệ thống</title>
	<meta http-equiv="Content-Language" content="en-us">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php
if($redirect!="")
  echo "<meta http-equiv=\"refresh\" content=\"3;url= $redirect\">";
?>
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
	<td style="padding: 10px" class="titlearl" align="center"><?php echo $content;?></td></tr></table>
	</td>
	</tr>
	</table>
	</body>
	</html>
<?php
}
# Cấu hình database
$config_server = array(
	'type' 		 => 'mysql', // Loại cơ sở dữ liệu mssql hoặc mysql
	'server' 	 => 'localhost', // Địa chỉ cơ sở dữ liệu. Localhost hoặc 127.0.0.1
	'username'   => 'news', // Tên đăng nhập vào cơ sở dữ liệu
	'password'   => 'WMOX9iMLLK3', //WMOX9iMLLK3 Mật khẩu vào cơ sở dữ liệu
	'database'   => 'news', // Database sử dụng
	'prefix'     => 'adoosite',  //tien to dau cua bang
	'hosting'	 => true // Nếu sử dụng hosting điền true , sử dụng server điền false
);

if($config_server['type'] == 'mysql')
{
	$conn = @mysql_connect($config_server['server'], $config_server['username'], $config_server['password']);
	if(!$conn){
      $conn = @mysql_connect('localhost', 'root', '111111');
      if(!$conn){die('Kết nối MySQL thất bại');}
    }
	mysql_select_db($config_server["database"],$conn);
}elseif($config_server['type'] == 'mssql')
{
	// tạo đối tượng database
	$db = &ADONewConnection('mssql'); 
	// kết nối cơ sở dữ liệu
	$connect_mssql = $db->Connect($config_server['server'],$config_server['username'],$config_server['password'],$config_server['database']); 
	if (!$connect_mssql){
      $connect_mssql = $db->Connect('localhost','root','111111','news');
        if (!$connect_mssql){die("Lỗi , không thể kết nối tới SQL Server");}
      }
}else{die('Yêu cầu thiết lập đúng <pre>$config_server[\'type\']</pre> là mssql hoặc mysql');}

function updateuserlog($user_id, $title,$money_old, $money, $money_new, $status, $action) {
  global $client_ip, $config_server;
	mysql_query("INSERT INTO {$config_server['prefix']}_user_log (id, user_id, title, money_old, money, money_new, status, dateline, ip_add, action) VALUES (NULL, '$user_id', '$title', '$money_old', '$money', '$money_new', '$status', '".time()."', '$client_ip','$action')");
}
function updatedocumentorder($user_buy, $user_sale, $documentid, $price) {
  global $client_ip, $config_server;
	mysql_query("INSERT INTO {$config_server['prefix']}_document_order (id, user_buy, user_sale, documentid, price, time) VALUES (NULL, '$user_buy', '$user_sale', '$documentid', '$price', '".time()."')");
}
//function checkUser() {
	
	//if (isset($_SESSION[USER_SESS]) && !empty($_SESSION[USER_SESS])) {
//kiem tra tai khoan nguoi dung co ton tai khong
$userArr = explode(';', $_SESSION[USER_SESS]);
$results = mysql_query("SELECT id, title, fullname, pass, address, phone, money, mep, email, folder, downloads_free FROM {$config_server['prefix']}_user WHERE (fullname='".$userArr[0]."' OR email='".$userArr[0]."') AND pass='".$userArr[1]."'");
if (!$results) {
  system_report("Người dùng không tồn tại, mời bạn đăng ký tài khoản mới.",'index.php?f=user&do=register');
  exit;
}
else
{
  $rows = mysql_fetch_array($results);
}
        
//echo $rows['money'];
//die();
//}
//$userInfo = checkUser();
//if (!$userInfo) unset($userInfo);

//if($config_server['type'] == 'mysql')
//		{
//			$query_update = "UPDATE `".$config_money['Table']."` SET `".$config_money['FieldChuaTien']."` = `".$config_money['FieldChuaTien']."` + '".$TienDuocHuong."' WHERE `".$config_money['FieldUsername']."` = '".$TxtAccount."';";
//			mysql_query($query_update);
//		}elseif($config_server['type'] == 'mssql')
//		{
//			$query_update = "UPDATE ".$config_money['Table']." SET ".$config_money['FieldChuaTien']." = ".$config_money['FieldChuaTien']." + ".$TienDuocHuong." WHERE ".$config_money['FieldUsername']." = '".$TxtAccount."';";
//			$db->Execute($query_update);
//		}
//kiem tra tai khoan dang nhap
if(!isset($ck_user)){
    system_report('Thông báo: để tải tài liệu mời bạn đăng nhập!','index.php?f=user&do=login');
    exit;
}

//$ck_user = $_SESSION[USER_SESS];
//if (!defined('CMS_SYSTEM')) die();
//if (!defined('iS_USER') || !isset($userInfo) || !isset($ck_user)){
//	die('<script>alert("Thông báo: để tải tài liệu mời bạn đăng nhập!"); window.location.href="'.url_sid("index.php?f=user&do=login").'";</script>');
//die('<script>alert("Thông báo: để tải tài liệu mời bạn đăng nhập!"); window.location.href="'.url_sid("index.php?f=user&do=login").'";</script>');
//}
//$result_user = $db->sql_query("SELECT downloads_free FROM ".$config_server['prefix']."_user  WHERE id=".$usernfo['id']."");
//$error='';
//$err=0;
//
//
$resulta = mysql_query("SELECT n.id , n.code, n.catid, n.title, n.time, n.price, n.fattach, n.hits_download, n.user_id, u.fullname, u.folder, u.money, u.mep, n.link_extend FROM ".$config_server['prefix']."_document AS n,".$config_server['prefix']."_document_cat AS c,".$config_server['prefix']."_user AS u WHERE $where n.catid=c.catid AND n.active=1 AND n.user_id=u.id");
if (!$resulta){
  system_report("Tài liệu không tồn tại.",'index.php');
  exit;
}
//kiem tra tai lieu thuc tai thu muc co ton tai khong.
$rowa = mysql_fetch_array($resulta);
if ($rowa['folder']=="")
      $folder = 'guest/';
  else
      $folder = $rowa['folder'].'/';
  $file_path = 'files/document/'.$folder.$rowa['fattach'];
  if(!file_exists($file_path))
  {
    system_report("Tài liệu không tồn tại.",'index.php');
    exit;
  }
  $file_name = $fattach;

//kiem tra tai khoan da tai tai lieu nay chua va dua ra canh bao de tranh truong hop tai nhieu lan
// kiem tra tai khoan da tai trong ngay thi bo qua tru tien tai khoan
$ydownload="";
if($yep==0){
  $gettoday = date('Y-m-d');
  $resultss = mysql_query("SELECT user_buy, documentid FROM {$config_server['prefix']}_document_order WHERE user_buy=".$rows['id']." AND documentid=".$rowa['id']." AND DATE(FROM_UNIXTIME(time))='$gettoday' LIMIT 1");
  if ($resultss) {
	$rowss = mysql_fetch_array($resultss);
    $ydownload=$rowss['documentid'];
  }
  else
  {
    $ydownload="";
    $resultsss = mysql_query("SELECT user_buy, documentid FROM {$config_server['prefix']}_document_order WHERE user_buy=".$rows['id']." AND documentid=".$rowa['id']." AND DATE(FROM_UNIXTIME(time))<>'$gettoday' LIMIT 1");
    if ($resultss) {
      system_report("<h1>Tài liệu này đã được bạn tải trước đó.Nếu tài liệu lỗi hoặc bạn tải nhưng tài liệu không đúng bạn có thể gửi báo lỗi hoặc <a href=\"http://thuvienxaydung.net/contact/\"><strong>click vào đây</strong></a> để liên hệ với chúng tôi. Bạn có chắn chắn vẫn muốn tải tài liệu này xin mời bạn <a href=\"http://thuvienxaydung.net/download.php?u=".$code."&yep=1\"><strong>click vào đây</strong></a> để tiếp tục tải liệu</h1>",'');
      exit;
    }
  }
}
//die($resultss.$ydownload. "SELECT user_buy, documentid FROM {$config_server['prefix']}_document_order WHERE user_buy=".$rows['id']." AND documentid=".$rowa['id']." AND DATE(FROM_UNIXTIME(time))='$gettoday'");

//kiem tra luot tai nguoi dung cho phep
//mien phi 3 luot tai ban  dau

if($rowa['price']==0)
{
  //tai lieu mien phi
  if($rows['downloads_free']>0)
  {
    //kiem tra ep trong tai khoan lon hon ep tai lieu
    
    //if($rows['money']>=$rowa['price'])
    //{
    
    if($ydownload==""){
      //cap nhat luot tai
      mysql_query("UPDATE adoosite_document SET hits_download=hits_download+1 WHERE code='$code'");
       //cap nhat luot tai mien phi
      mysql_query("UPDATE adoosite_user SET downloads_free=downloads_free-1 WHERE id=".$rows['id']."");
      //tru tien tai khoan nguoi tai
      $money_udown_new	= $rows['money'] - $rowa['price'];
      mysql_query("UPDATE ".$config_server['prefix']."_user SET money=$money_udown_new WHERE id=".$rows['id']."");
  updateuserlog($rows['id'],'Tải tài liệu',$rows['money'],$rowa['price'],$money_udown_new,'-',$rowa['title']);
  //cong tien tai khoan nguoi dang

    $money_uup_new = $rowa['mep'] + $rowa['price'];
    mysql_query("UPDATE ".$config_server['prefix']."_user SET mep=$money_uup_new WHERE id=".$rowa['id']."");
    updateuserlog($rowa['user_id'],'Nhận tiền',$rowa['mep'],$rowa['price'],$money_uup_new,'+',$rowa['title']);
    //luot tai vao bang document_Order
      mysql_query("INSERT INTO adoosite_document_order SET user_buy=".$rows['id'].", user_sale=".$rowa['user_id']." WHERE code='$code'");
    updatedocumentorder($rows['id'],$rowa['user_id'],$rowa['id'],$rowa['price']);
    }
    get_document($rowa['folder'],$rowa['fattach'],$rowa['link_extend']);
      
     
      exit;
    //}
    //else
    //{
    //  system_report("Tài khoản không đủ Ep để tải tài liệu.",'index.php');
    //}
  }
  else{
    if($rows['money']>0)
    {
       if($ydownload==""){
      //cap nhat luot tai
      mysql_query("UPDATE adoosite_document SET hits_download=hits_download+1 WHERE code='$code'");
      //tru tien tai khoan nguoi tai
      $money_udown_new	= $rows['money'] - $rowa['price'];
  mysql_query("UPDATE ".$config_server['prefix']."_user SET money=$money_udown_new WHERE id=".$rows['id']."");
  updateuserlog($rows['id'],'Tải tài liệu',$rows['money'],$rowa['price'],$money_udown_new,'-',$rowa['title']);
  //cong tien tai khoan nguoi dang

    $money_uup_new = $rowa['mep'] + $rowa['price'];
  mysql_query("UPDATE ".$config_server['prefix']."_user SET mep=$money_uup_new WHERE id=".$rowa['user_id']."");
  updateuserlog($rowa['user_id'],'Nhận tiền',$rowa['mep'],$rowa['price'],$money_uup_new,'+',$rowa['title']);
   //luot tai vao bang document_Order
  updatedocumentorder($rows['id'],$rowa['user_id'],$rowa['id'],$rowa['price']);
    }
  get_document($rowa['folder'],$rowa['fattach'],$rowa['link_extend']);
  exit;
    }
    else
    {
      system_report("Tài khoản không đủ Ep để tải tài liệu. Mời bạn nạp tiền để có thể tải tài liệu",'index.php?f=napthe&do=create');
      exit;
    }
  }
}
else{
  
  //tai lieu tinh phi
  if($rows['money']>=$rowa['price'])
  {
      if($ydownload==""){
    //cap nhat luot tai
      mysql_query("UPDATE adoosite_document SET hits_download=hits_download+1 WHERE code='$code'");
      //tru tien tai khoan nguoi tai
      $money_udown_new	= $rows['money'] - $rowa['price'];
  mysql_query("UPDATE ".$config_server['prefix']."_user SET money=$money_udown_new WHERE id=".$rows['id']."");
  updateuserlog($rows['id'],'Tải tài liệu',$rows['money'],$rowa['price'],$money_udown_new,'-',$rowa['title']);
  //cong tien tai khoan nguoi dang

      $money_uup_new = $rowa['mep'] + $rowa['price'];
      mysql_query("UPDATE ".$config_server['prefix']."_user SET mep=$money_uup_new WHERE id=".$rowa['user_id']."");
  updateuserlog($rowa['user_id'],'Nhận tiền',$rowa['mep'],$rowa['price'],$money_uup_new,'+',''.$rowa['fullname'].' nhận tiền thành viên tải tài liệu '.$rowa['title'].'');
   //luot tai vao bang document_Order
 updatedocumentorder($rows['id'],$rowa['user_id'],$rowa['id'],$rowa['price']);
    }
    get_document($rowa['folder'],$rowa['fattach'],$rowa['link_extend']);
    exit;
  }
  else
  {
    system_report("Tài khoản không đủ Ep để tải tài liệu. Mời bạn nạp tiền để có thể tải tài liệu",'index.php?f=napthe&do=create');
    exit;
  }
}

function get_document($folder,$fattach,$link_extend)
{
  global $code, $userInfo;
  if($link_extend=="")
  {
			if(!empty($folder) && !empty($fattach))
			{
				set_time_limit(0);
				
				//if(!verifyHash($row['fattach'],$file))
				//{
				//	$codefile=makeHash($row['fattach']);
				//mysql_query("UPDATE adoosite_document SET code='".$codefile."' WHERE id=".$row['id']."");
				////die($_SERVER['REMOTE_ADDR'].$fileName.date('d.m.Y,H:i').SECURITY_CODE);
				////die($codefile.'/'.$file);
				////die("UPDATE adoosite_document SET code=".$Code." WHERE id=".$row['id']."");
				//output_file('document/'.$row['folder'].'/'.$row['fattach'], $row['fattach'], '', $updata);
				//}
				//else
				//{
				//	
				//}
				//if(verifyHash($fattach,$file))
				//{
				//	exit('File not found 2 !');
				//	header('Location: /linktamthoi/download.php?file='.$fileName);
				//	header('Location: ../files/download.php?file='.$fileName);
				//	$codefile=makeHash($row['fattach']);
				//	mysql_query("UPDATE adoosite_document SET code='".$codefile."' WHERE id=".$row['id']."");
				//}
				//else
				//{
					$filePath='files/document/'.$folder.'/'.$fattach;
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
					$file_extension = strtolower(substr(strrchr($filePath,"."),1));
					if(array_key_exists($file_extension, $known_mime_types)){
					   $mime_type=$known_mime_types[$file_extension];
					} else {
					   $mime_type="application/force-download";
					};
				};
				// required for IE, otherwise Content-Disposition may be ignored
					//if(ini_get('zlib.output_compression'))
					//ini_set('zlib.output_compression', 'Off');
					header('Content-Type:' . $mime_type);
					header('Content-Disposition: attachment; filename="'.urlencode($fattach).'"');
					header("Content-Transfer-Encoding: binary");
					//header('Accept-Ranges: bytes');
					header('Content-Description: File Transfer');
					header('Expires: 0');
					header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
					header('Pragma: public');
					header('Content-Length: ' . filesize($filePath));
					ob_clean();
					flush();
					readfile($filePath);
					//output_file($filePath,$row['fattach'],'');
  
				//}
			}
  }		//}
  else
  {
    
    header("Location: $link_extend");
  }
}
  //echo "<script language=\"javascript\" type=\"text/javascript\">";
		   //echo "alert('Bạn đã tải tài liệu $title phí tải ".bsVndDot($price)."đ!');";
	//	   echo "window.location.href=\"$urlsite/files/document.php?file=$code\"";
		   //echo 'history.go(-1);';
	//	   echo "</script>";
  //header("Location: document.php?file=$file_get");
//
//}
//else
//{

mysql_close($conn);
?>
