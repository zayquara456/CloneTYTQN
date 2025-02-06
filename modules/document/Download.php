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
if (!file_exists("config.php")) die();
define('CMS_SYSTEM', true);
@require_once("config.php");
global $urlsite;

$ck_user = $_SESSION[USER_SESS];
if (!defined('CMS_SYSTEM')) die();
if (!defined('iS_USER') || !isset($userInfo) || !isset($ck_user)){
	die('<script>alert("Thông báo: để tải tài liệu mời bạn đăng nhập!"); window.location.href="'.url_sid("index.php?f=user&do=login").'";</script>');

}
$result_user = $db->sql_query("SELECT downloads_free FROM ".$prefix."_user  WHERE id=".$userInfo['id']."");

if($db->sql_numrows($result_user) != 1) {
	$error  = 'Tài liệu không tồn tại!';
	$err    = 1;
}
else
{
  list($downloads_free) = $db->sql_fetchrow($result_user);
}
$error='';
$err=0;
$where="";
$code = $_GET['u'];
if($code!="")
	$where.="n.code='$code' AND ";

$result = $db->sql_query("SELECT n.id , n.code, n.catid, n.title, n.time, n.price, n.fattach, n.hits_download, n.user_id, u.fullname, u.folder, u.money FROM ".$prefix."_document AS n,".$prefix."_document_cat AS c,".$prefix."_user AS u WHERE $where n.catid=c.catid AND n.active=1 AND n.user_id=u.id AND n.alanguage='$currentlang'");

if($db->sql_numrows($result) != 1) {
	$error  = 'Tài liệu không tồn tại!';
	$err    = 1;
}
else
{
  list($id, $code, $catid, $title, $time, $price, $fattach, $hits_download, $user_id, $fullname, $folder, $money) = $db->sql_fetchrow($result);
  //echo $money;
  //kiem tra tai khoan con tien khong
  if($price >= $userInfo['money'])
  {
	
   // $error  = 'Tài khoản không đủ tiền tải tài liệu, mời bạn nạp tiền vào tài khoản!';
    //$err    = 1;
	if($downloads_free <= 0)
	{
	  $error  = 'Tài khoản hết lượt tải theo quy định.(mỗi ngày tài khoản của bạn được tải tối đa 3 tài liệu)!';
	  $err    = 1;
	}
	
  }
  else{
	
    //$error  = 'Tài khoản không đủ tiền tải tài liệu, mời bạn nạp tiền vào tài khoản!';
    //$err    = 1;
		//kiem tra luot download mien phi

  }
  
  if ($folder=="")
      $folder='guest/';
  else
      $folder= $folder.'/';
  $file_path = 'files/document/'.$folder.$fattach;
  if(!file_exists($file_path))
  {
    $error  = 'Tài liệu không tồn tại!';
    $err    = 1;
  }
  $file_name = $fattach;
}
if(!$err){
  //tru tien tai khoan nguoi tai
  $money_udown		= $userInfo['money'];
  $money_udown_new	= $money_udown - $price;
  $db->sql_query("UPDATE ".$prefix."_user SET money=money-$price WHERE id=".$userInfo['id']."");
  updateuserlog($userInfo['id'],'Tải tài liệu',$money_udown,$price,$money_udown_new,'-',''.$userInfo['fullname'].' tải tài liệu '.$title.'');
  //cong tien tai khoan nguoi dang
  if($userInfo['id']==$user_id)
  {
	$money_uup		= $money_udown_new;
  }
  else
  {
	$money_uup		= $money;
  }
  $money_uup_new	= $money_uup + $price;
  $db->sql_query("UPDATE ".$prefix."_user SET money=money+$price WHERE id=".$user_id."");
  updateuserlog($user_id,'Nhận tiền',$money_uup,$price,$money_uup_new,'+',''.$fullname.' nhận tiền thành viên tải tài liệu '.$title.'');
	
	$file = $code;

		$result = mysql_query("SELECT n.id, u.folder, n.fattach, n.code FROM adoosite_user AS u, adoosite_document AS n WHERE u.id=n.user_id AND n.code='$file'");
		
		if($result){
		$row = mysql_fetch_array($result);
			//cap nhat ma tai lieu
			if(!empty($row['folder']) && !empty($row['fattach']))
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
				if(verifyHash($row['fattach'],$file))
				{
					//exit('File not found 2 !');
					//header('Location: /linktamthoi/download.php?file='.$fileName);
					//header('Location: ../files/download.php?file='.$fileName);
					$codefile=makeHash($row['fattach']);
					mysql_query("UPDATE adoosite_document SET code='".$codefile."' WHERE id=".$row['id']."");
				}
				else
				{
					$filePath='files/document/'.$row['folder'].'/'.$row['fattach'];
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
					header('Content-Disposition: attachment; filename="'.urlencode($row['fattach']).'"');
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
					
					mysql_query("UPDATE adoosite_document SET hits_download=hits_download+1 WHERE code='$file'");
					//cap nhat luot tai mien phi
					mysql_query("UPDATE adoosite_user SET downloads_free=downloads_free-1 WHERE id=".$userInfo['id']."");
					exit;
				}
			}
		}
  //echo "<script language=\"javascript\" type=\"text/javascript\">";
		   //echo "alert('Bạn đã tải tài liệu $title phí tải ".bsVndDot($price)."đ!');";
	//	   echo "window.location.href=\"$urlsite/files/document.php?file=$code\"";
		   //echo 'history.go(-1);';
	//	   echo "</script>";
  //header("Location: document.php?file=$file_get");

}
else
{
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
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
<td style="padding: 10px" class="titlearl" align="center"><?php echo $error;?></td></tr></table>
</td>
</tr>
</table>
</body>
</html>
<?php
}
?>
