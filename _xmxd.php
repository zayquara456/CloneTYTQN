<?php
if (!file_exists("config.php")) die();
define('CMS_SYSTEM', true);
@require_once("config.php");
$code = $_REQUEST['code'];//Mã chính
$subCode = $_REQUEST['subCode'];// Mã phụ
$mobile = $_REQUEST['mobile']; // Số điện thoại nhắn tin
$serviceNumber = $_REQUEST['serviceNumber']; //Số dịch vụ
$info = $_REQUEST['info'];
//$info = "tungdeptrai   matkhau1   matkhau2";
$info = str_replace('     ',' ',$info); // Nôi dung tin nhắn 
$info = str_replace('    ',' ',$info); // Nôi dung tin nhắn 
$info = str_replace('   ',' ',$info); // Nôi dung tin nhắn 
$info = str_replace('  ',' ',$info); // Nôi dung tin nhắn
$info = trim($info);
//$chuoi = "tungdeptrai::matkhau1::matkhau2";

$arr= explode(' ',$info);
$error="";
$nummobile = '0'.substr($mobile, 2);  // returns "cde"
//$fullname = nospatags($arr[0]);
$password=randomPassword(8);//$arr[2];
//$password2=nospatags($arr[2]);
//$email = nospatags($arr[0]);

//$address='';
$phone=nospatags($nummobile);
	$db->sql_query("SELECT  phone FROM {$prefix}_user WHERE phone='$phone'");
	if($db->sql_numrows($result) <= 0) {
		$err = 1;
		$error = "Tai khoan khong ton tai";
	}

	if (!$err) {
			$db->sql_query("UPDATE {$prefix}_user SET activationCode=NULL, actives=1 WHERE phone='$phone'");
			$responseInfo = "Ban xac minh tai khoan thanh cong.\n";
			$responseInfo = $responseInfo."THU VIEN XAY DUNG.NET";	
			echo "0|".$responseInfo;
	//		$responseInfo = "Hi {mobile}\nCam on ban da su dung dich vu.\n";
	//$responseInfo = $responseInfo. "Ma chinh: ".$code ."\n";
	//$responseInfo = $responseInfo."Ma phu: ".$subCode ."\n";
	//$responseInfo = $responseInfo."Dau so: ".$serviceNumber ."\n\n";
	//$responseInfo = $responseInfo."SMS.VN";	
	//echo "0|".$responseInfo.$info;	
	}
	else
	{
		$responseInfo = $error;
		echo "0|".$responseInfo;
	}

function randomPassword($length = 8) {
    $alphabet = "abcdefghijklmnopqrstuwxyz0123456789";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < $length; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}
?>
