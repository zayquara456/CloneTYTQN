<?php
$ck_user = $_SESSION[USER_SESS];
if (!defined('CMS_SYSTEM')) die();
if (!defined('iS_USER') || !isset($userInfo) || !isset($ck_user)){
	header("Location: ".url_sid("index.php?f=user&do=login")."");
	exit();
}
$page_title="Nạp thẻ viễn thông";
include("header.php");
$cryptinstall="captcha/cryptographp.fct.php";
include $cryptinstall; 
# Include các file cần thiết
include_once('adodb/adodb.inc.php');
include 'class/class.gateWay.php';
//if($_SESSION['ck_nap']==null){$_SESSION['ck_nap']=0;}
session_register("ck_nap");
//if($_SESSION['ck_nap']==0){$_SESSION['ck_nap']=="";}
$TxtMenhGia="";
# Cấu hình ketnoipay.com
$config_ketnoipay = array(
	# Sau khi đăng nhập ở http://id.cbviet.net/ketnoipay , bạn có thể lấy được PartnerID tại menu thông tin tài khoản , rồi điền vào đây
	
	'TxtPartnerId'  => $txtpartnerid, 
	# Sau khi đăng nhập ở http://id.cbviet.net/ketnoipay , bạn hãy thiết lập chữ kí giao dịch signal , rồi điền vào đây
	'TxtSignal' 	=> $txtsignal 
);
# Cấu hình table chứa tiền , sử dụng cho các Game Private
$config_money = array(
	'Table' 		=> $prefix.'_user',
	'FieldChuaTien' => 'money',
	'FieldUsername' => 'email'
);
$config_server = array(
	'type' 		 => 'mysql', // Loại cơ sở dữ liệu mssql hoặc mysql
	'server' 	 => '', // Địa chỉ cơ sở dữ liệu. Localhost hoặc 127.0.0.1
	'username'   => '', // Tên đăng nhập vào cơ sở dữ liệu
	'password'   => '', // Mật khẩu vào cơ sở dữ liệu
	'database'   => '', // Database sử dụng
	'hosting'	 => true // Nếu sử dụng hosting điền true , sử dụng server điền false
);

OpenTab("Nạp thẻ viễn thông");
//echo "<div class=\"content\">";
//echo "<div class=\"div-home\">";

$code = $serial = $content = $email = $name = $err_code = $err_serial = $error_captcha = '';
if( isset($_POST['subup']) && $_POST['subup'] == 1) {
	$err = 0;
	$telecom = intval($_POST['telecom']);
	$code = $escape_mysql_string(trim($_POST['txtcode']));
	$serial = $escape_mysql_string(trim($_POST['txtserial']));
	
	if (empty($code)) {
		$err_code = "<font color=\"red\">Mời bạn nhập mã code</font>";
		$err = 1;
	}
	if (empty($serial)) {
		$err_serial = "<font color=\"red\">Mời bạn nhập serial</font>";
		$err = 1;
	}
	if($_SESSION['ck_nap'] >= 5) {
		if (!chk_crypt($_POST['captcha'])) 
		{
			$err = 1;
			$error_captcha = "<font  color=\"red\">Mã bảo xác nhận không đúng!</font>";
		}
	}
	if (!$err && $userInfo['id']!=0 && $ck_user!="") {
		
		//Thiết lập loại thẻ và cổng kết nối
	   if($config_server['hosting'])
	   {
		   switch($telecom)
		   {
			   case 1:
				   $TxtType = 'VMS';
				   $TxtUrl  = 'http://pay.ketnoipay.com/VINAMOBI';
			   break;
			   case 2:
				   $TxtType = 'VNP';
				   $TxtUrl  = 'http://pay.ketnoipay.com/VINAMOBI';
			   break;
			   case 3:
				   $TxtType = 'VTT';
				   $TxtUrl  = 'http://pay.ketnoipay.com/VIETTEL';
			   break;
			   case 4:
				   $TxtType = 'GATE';
				   $TxtUrl  = 'http://pay.ketnoipay.com/GATE';
			   break;
			   case 5:
				   $TxtType = 'VTC';
				   $TxtUrl  = 'http://pay.ketnoipay.com/VTC';
			   break;
		   }
	   }else{
		   switch($telecom)
		   {
			   case 1:
				   $TxtType = 'VMS';
				   $TxtUrl  = 'http://pay.ketnoipay.com/VINAMOBI';
			   break;
			   case 2:
				   $TxtType = 'VNP';
				   $TxtUrl  = 'http://pay.ketnoipay.com/VINAMOBI';
			   break;
			   case 3:
				   $TxtType = 'VTT';
				   $TxtUrl  = 'http://pay.ketnoipay.com/VIETTEL';
			   break;
			   case 4:
				   $TxtType = 'GATE';
				   $TxtUrl  = 'http://pay.ketnoipay.com:64986';
			   break;
			   case 5:
				   $TxtType = 'VTC';
				   $TxtUrl  = 'http://pay.ketnoipay.com:64987';
			   break;
		   }
	   }
	   # Gửi thẻ lên máy chủ FPAY
	   $TxtTransid	= md5(generate_code(6));
	   $TxtKey   	= md5(trim($config_ketnoipay['TxtPartnerId'].$TxtType.$TxtTransid.$code.$config_ketnoipay['TxtSignal']));
	   $gateWay  	= new gateWay($config_ketnoipay['TxtPartnerId'],$TxtType,$code,$serial,$TxtTransid,$TxtKey,$TxtUrl);
	   $response = $gateWay->ReturnResult();
	   
	   //lay phan tram khuyen mai cho tai khoan
		$resultpromotion=$db->sql_query("SELECT id, username, napthe, promotion, time, active FROM {$prefix}_napthe_promotion WHERE napthe=$telecom AND username =".$userInfo['id']." AND active=1");
		if($db->sql_numrows($resultpromotion) > 0) {
			list($pmt_id, $pmt_username, $pmt_napthe, $pmt_promotion, $pmt_time, $pmt_active) = $db->sql_fetchrow($resultpromotion);
			$promotionkm = $pmt_promotion/100;
		}
		else{
			$resultall=$db->sql_query("SELECT id, username, napthe, promotion, time, active FROM {$prefix}_napthe_promotion WHERE napthe=$telecom AND username=0 AND active=1");
			if($db->sql_numrows($resultall) >0) {
				list($pmt_id, $pmt_username, $pmt_napthe, $pmt_promotion, $pmt_time, $pmt_active) = $db->sql_fetchrow($resultall);
				$promotionkm = $pmt_promotion/100;
			}
		}
		$tiennhan=0;
		$money_old=$userInfo['money'];
		$moneynew=0;
		//Xử lý kết quả
	   if(strpos($response,'RESULT:10') !== false) // thẻ đúng
	   {
		   $TxtMenhGia	   = intval(str_replace('RESULT:10@','',$response));
		   
		   $TienDuocHuong = $TxtMenhGia;
		   
		   //cong tien vao tai khoan
		   $tiennhan=$TienDuocHuong*$promotionkm;
		   $moneynew=$money_old+$tiennhan;
		   $db->sql_query("UPDATE {$prefix}_user SET money=$moneynew WHERE email='".$userInfo['email']."'");
		   //cap nhat the vao data
		   $query = "INSERT INTO {$prefix}_napthe (id, userid, telecom, code, serial, time, status, price, active, transid) VALUES (NULL, ".$userInfo['id'].", '$telecom', '$code', '$serial', ".time().", 1,'$tiennhan', 1,'$TxtTransid')";
		   $db->sql_query($query);
		   
			updateuserlog($userInfo['id'],"Nạp thẻ", $money_old, $tiennhan, $moneynew, "+","".$userInfo['fullname']." Nạp mã thẻ điện thoại $code");
			$result = 'Thẻ đúng và có mệnh giá '.$TxtMenhGia;
		   echo "<script language=\"javascript\" type=\"text/javascript\">";
		   echo "alert('Thẻ $TienDuocHuong của bạn đã nạp thành công!');";
		   echo "window.location.href=\"index.php?f=".$module_name."&do=create\"";
		   echo "</script>";
		   unset($_SESSION['ck_nap']);
		   
	   }elseif(strpos($response,'RESULT:03') !== false || strpos($response,'RESULT:05') !== false || strpos($response,'RESULT:07') !== false || strpos($response,'RESULT:06') !== false) // thẻ sai
	   {
		   $result = 'Mã thẻ cào hoặc seri không chính xác.'.$response;
	   }elseif(strpos($response,'RESULT:08') !== false)
	   {
		   $result = 'Thẻ đã gửi sang hệ thống rồi. Không gửi thẻ này nữa.'.$response;
	   }elseif(strpos($response,'RESULT:12') !== false)
	   {
		   $result = 'Bạn phải nhập seri thẻ.'.$response;
	   }elseif(strpos($response,'RESULT:11') !== false)
	   {
			$moneynew=$money_old+$tiennhan;
			//cap nhat the vao data
		   $query = "INSERT INTO {$prefix}_napthe (id, userid, telecom, code, serial, time, status, price, active, transid) VALUES (NULL, ".$userInfo['id'].", '$telecom', '$code', '$serial', ".time().", 0,'0', 1, '$TxtTransid')";
		   $db->sql_query($query);
		   
			updateuserlog($userInfo['id'],"Nạp thẻ", $money_old, $tiennhan, $moneynew, "+","".$userInfo['fullname']." Nạp mã thẻ điện thoại $code [Thẻ trễ đang trờ xử lý]");
		   $result = 'Thẻ đã gửi sang hệ thống nhưng bị trễ.'.$response;
	   }elseif(strpos($response,'RESULT:99') !== false || strpos($response,'RESULT:00') !== false || strpos($response,'RESULT:01') !== false || strpos($response,'RESULT:04') !== false || strpos($response,'RESULT:09') !== false)
	   {
		   $result = 'Hệ thống nạp thẻ đang bảo trì. Mã bảo trì là '.$response;
	   }else{
		   $result = 'Có lỗi xảy ra trong quá trình nạp thẻ. Vui lòng quay lại sau.';
	    }
	   //$query = "INSERT INTO {$prefix}_napthe (id, userid, telecom, code, serial, time, status, price, active) VALUES (NULL, ".$userInfo['id'].", '$telecom', '$code', '$serial', ".time().", 0,'$TxtMenhGia', 1)";
		   //$db->sql_query($query);
		   $serial= $_POST['txtserial'];
		   $code= $_POST['txtcode'];
		//updatenapthelog($userInfo['id'],"Nạp thẻ",0, "+","Nạp mã thẻ điện thoại ".CutString($code,6)." - lỗi thẻ");
		 $moneynew=$money_old+$tiennhan;
		updatenapthelog($userInfo['id'],"Nạp thẻ", $money_old, $tiennhan, $moneynew, "+","".$userInfo['fullname']." Nạp mã thẻ điện thoại code: $code | serial: $serial | $result");
		$_SESSION['ck_nap']++;
		die('<script>alert("Thông báo: '.$result.'");history.go(-1);</script>');
	   
	}
}

echo "<form action=\"index.php?f=$module_name&do=$do\" method=\"POST\" onsubmit=\"return Check_Valid(this);\">";
echo "<table class=\"tableborder\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\" width=\"100%\">\n";
echo "<tr>\n";
echo "<td width=\"80px\" class=\"row1\">Nhà mạng:</td>\n";
echo '<td class="row1"><select class="ddl" id="telecom" name="telecom">';
global $telecom_arr;
	foreach($telecom_arr as $key => $value)
	{
		if($telecom==$value){$selected='selected="selected"';}
		else{$selected='';}
		echo "<option value=\"$value\" $selected>$key</option>";
	}
echo'</select></td>';
	

echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"80px\" class=\"row1\">Mã thẻ:</td>\n";
echo "<td width=\"80px\" class=\"row1\"><input type=\"text\" id=\"txtcode\" value=\"$code\" name=\"txtcode\"  size=\"30\">$err_code</td>\n";
echo "</td></tr>\n";
echo "<tr>\n";
echo "<td class=\"row1\">Số serial: </td>\n";
echo "<td class=\"row1\"><input type=\"text\" id=\"txtserial\" name=\"txtserial\"  value=\"$serial\" size=\"30\">$err_serial</td>\n";
echo "</tr>\n";
if($_SESSION['ck_nap'] >=5) {
?>
<tr>
<td class="row1">Mã xác nhận:<span class="risk">*</span></td>
<td class="row1"><input type="text" name="captcha"  id="captcha" size="10">
<?php dsp_crypt(0,1); ?><?echo $error_captcha?>
</td>
</tr>
<?php
}
echo "<tr><td>&nbsp;</td><td style=\"padding:5px 3px 3px 130px;\"><input type=\"hidden\" name=\"subup\" value=\"1\"><input class=\"sb_but1\" type=\"submit\" name=\"submit\" value=\"Nạp thẻ\"></td></tr>";
echo "</table>";
echo"</form>";

$result = $db->sql_query("SELECT content FROM ".$prefix."_gentext WHERE textname='telecom' AND alanguage='$currentlang'");
list($content) = $db->sql_fetchrow($result);
?>
<div><?php echo $content?></div>
<?php
//echo "</div>";
//echo "</div>";
CloseTab();

OpenTab("Danh sách đã nạp");
echo "<div class=\"content\">";
$perpage = 15;
$page = isset($_GET['page']) ? intval($_GET['page']) : (isset($_POST['page']) ? intval($_POST['page']) :1);
$from = isset($_GET["from"]) ? $_GET["from"] : "";
$to = isset($_GET["to"]) ? $_GET["to"] : "";
$action = isset($_GET["action"]) ? $_GET["action"] : "";
$offset = ($page-1) * $perpage;

$total = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_napthe WHERE userid=".$userInfo['id']." "));
$result = $db->sql_query("SELECT  id,userid,telecom,code,serial,time,status,price,active  FROM ".$prefix."_napthe WHERE userid=".$userInfo['id']." ORDER BY time DESC LIMIT $offset, $perpage");
?>

<?php
if($db->sql_numrows($result) > 0) {

echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
echo "<tr style=\"background:#f9f9f9\">\n";
echo "<td class=\"row1sd\" width=\"60\">Thời gian</td>\n";
echo "<td class=\"row1sd\" width=\"30\">Nhà mạng</td>\n";
echo "<td class=\"row1sd\" width=\"50\">Mã code</td>\n";
echo "<td class=\"row1sd\"  align=\"left\" width=\"50\">Mã serial</td>\n";
echo "<td class=\"row1sd\" align=\"right\" width=\"50\">Số tiền (VNĐ)</td>\n";
echo "<td class=\"row1sd\" align=\"left\" width=\"30\">Trạng thái</td>\n";
echo "</tr>\n";
$cur_ar = array(_VND,_USD);
$i =0;
while(list($id,$userid,$telecom,$code,$serial, $time, $status, $price ,$active) = $db->sql_fetchrow($result)) {
if($i%2 == 1) {
	$css = "row1";
	$style_css="style=\"background:#f9f9f9;\"";
	}
else {
	$css ="row3";
	$style_css="style=\"background:#ffffff;\"";
}
if($status==0){$status="Đang xử lý";}
elseif($status==1){$status="Nạp thành công";}
echo "<tr $style_css>\n";
echo "<td class=\"$css\">".ext_time($time, 2)."</td>\n";
echo "<td class=\"$css\">".show_telecom($telecom)."</td>\n";
echo "<td class=\"$css\">$code</td>\n";
echo "<td class=\"$css\"  align=\"left\">$serial</td>\n";
echo "<td class=\"$css\" align=\"right\"><font color=\"red\">".bsVndDot($price)."</font></td>\n";
echo "<td class=\"$css\"  align=\"left\">$status</td>\n";
echo "</tr>\n";
$i ++;	
}


echo "<tr><td class=\"row4\" colspan=\"9\">";
if($total > $perpage) {
	echo "<div class=\"fr\">";	
	$pageurl = "index.php?f=napthe&do=create";
	echo paging($total,$pageurl,$perpage,$page);
	echo "</div>";
}	
echo "</td></tr>";
echo "</table></form>";
}else{
	echo "<center>Chưa phát sinh giao dịch.</center>";
}
echo "</div>";

CloseTab();
include("footer.php");
?>