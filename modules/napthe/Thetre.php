<?php
if (!defined('CMS_SYSTEM')) die();
# Cấu hình ketnoipay.com
$config_ketnoipay = array(
	# Sau khi đăng nhập ở http://id.cbviet.net/ketnoipay , bạn có thể lấy được PartnerID tại menu thông tin tài khoản , rồi điền vào đây
	
	'TxtPartnerId'  => $txtpartnerid, 
	# Sau khi đăng nhập ở http://id.cbviet.net/ketnoipay , bạn hãy thiết lập chữ kí giao dịch signal , rồi điền vào đây
	'TxtSignal' 	=> $txtsignal 
);

$TxtType    = mysql_escape_string($_GET['TxtType']);
$TxtTransId = mysql_escape_string($_GET['TxtTransId']);
$TxtMenhGia = mysql_escape_string($_GET['TxtMenhGia']);
$TxtKey 	= mysql_escape_string($_GET['TxtKey']);
$key	 	= mysql_escape_string($_GET['key']);

$key_knp = md5(trim($TxtType.$TxtTransId.$TxtMenhGia.$config_ketnoipay['TxtSignal']));

if (($key_knp==$TxtKey) && ($key==md5('222.255.28.173')) && (!empty($TxtType)) && (!empty($TxtTransId)) && (!empty($TxtMenhGia))) {
   // lay thong tin nguoi dung theo transid
   $resultinfo=$db->sql_query("SELECT u.id, u.fullname, u.email, u.money, n.id, n.telecom, n.transid FROM {$prefix}_user AS u,".$prefix."_napthe AS n WHERE transid='$TxtTransId' AND status=0");
	if($db->sql_numrows($resultinfo) > 0) {
		list($uid, $ufullname, $uemail, $umoney, $nid, $ntelecom) = $db->sql_fetchrow($resultinfo);
		
		$resultpromotion=$db->sql_query("SELECT id, username, napthe, promotion, time, active FROM {$prefix}_napthe_promotion WHERE napthe=$ntelecom AND username =".$uid." AND active=1");
		if($db->sql_numrows($resultpromotion) > 0) {
			list($pmt_id, $pmt_username, $pmt_napthe, $pmt_promotion, $pmt_time, $pmt_active) = $db->sql_fetchrow($resultpromotion);
			$promotionkm = $pmt_promotion/100;
		}
		else{
			$resultall=$db->sql_query("SELECT id, username, napthe, promotion, time, active FROM {$prefix}_napthe_promotion WHERE napthe=$ntelecom AND username=0 AND active=1");
			if($db->sql_numrows($resultall) >0) {
				list($pmt_id, $pmt_username, $pmt_napthe, $pmt_promotion, $pmt_time, $pmt_active) = $db->sql_fetchrow($resultall);
				$promotionkm = $pmt_promotion/100;
			}
		}
		
		$tiennhan=0;
		$money_old=$umoney;
		$moneynew=0;
		$TienDuocHuong = $TxtMenhGia;
		
		//cong tien vao tai khoan
		$tiennhan=$TienDuocHuong*$promotionkm;
		$moneynew=$money_old+$tiennhan;
		$db->sql_query("UPDATE {$prefix}_user SET money=$moneynew WHERE id='".$uid."'");
		//cap nhat the vao data
		$query = "UPDATE {$prefix}_napthe SET status=1, price='$tiennhan' WHERE id=$nid";
		$db->sql_query($query);
		updateuserlog($uid,'Nạp thẻ', $money_old, $tiennhan, $moneynew, "+","".$ufullname." Trả tiền thẻ trễ");
		updatenapthelog($uid,'Nạp thẻ', $money_old, $tiennhan, $moneynew, "+","".$ufullname." Trả tiền thẻ trễ mã giao dịch $TxtTransId");
		//$result = 'Thẻ đúng và có mệnh giá '.$TxtMenhGia;
		//die('<script>alert("Thông báo: '.$result.'");history.go(-1);</script>');
		echo 'RESULT:10@'; // result ketnoipay thanh cong
	}
	else
	{
		echo 'RESULT:00@';// result ketnoipay that bai
	}
	
   //lay phan tram khuyen mai cho tai khoan
	
}
else
{
	echo 'RESULT:00@';// result ketnoipay that bai
}

//include("footer.php");
?>