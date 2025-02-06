<?php
if (!file_exists("config.php")) die();
define('CMS_SYSTEM', true);
@require_once("../config.php");
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
//$chuoi = "tungdeptrai::matkhau1::matkhau2";

$arr= explode(' ',$info);
$error="";
$nummobile = '0'.substr($mobile, 2);  // returns "cde"
$fullname = nospatags($arr[2]);
$password=randomPassword();//nospatags($arr[3]);
$password2=randomPassword();//nospatags($arr[4]);
//$email = nospatags($arr[2]);
$address='';
$phone=nospatags($nummobile);

$db->sql_query("SELECT  fullname FROM {$prefix}_user WHERE fullname='$fullname'");
	if($db->sql_numrows($result) > 0) {
		$err = 1;
		$error = "Tai khoan da ton tai";
	}
	$db->sql_query("SELECT  phone FROM {$prefix}_user WHERE phone='$phone'");
	if($db->sql_numrows($result) > 0) {
		$err = 1;
		$error = "So dien thoai da duoc dang ky";
	}
	if (empty($email) || strlen($email)<6) {
		$err = 1;
		$error =  "Tai khoan lon hon 6 ky tu va nho hon 16 ky tu";
	}
	if (!$err) {
		$db->sql_query("INSERT INTO {$prefix}_user (id, group_id, email, title, fullname, pass, pass2, address, phone, actives, registrationTime, loginAttempt)  VALUES (null, 0, '', 0, '$fullname', '".md5($password)."', '".md5($password2)."', '$address', '$phone', 1, NOW(), 0)");
		$responseInfo = "Ban da dang ky thanh cong.\n";
		$responseInfo = $responseInfo."TK ".$email."\n";
		$responseInfo = $responseInfo."MK1 ".$password."\n";
		$responseInfo = $responseInfo."MK2 ".$password2."\n";
		$responseInfo = $responseInfo."THUVIENXAYDUNG.NET";	
		echo "0|".$responseInfo;
	//$responseInfo = "Hi {mobile}\nCam on ban da su dung dich vu.\n";
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
function randomPassword() {
    $alphabet = "abcdefghijklmnopqrstuwxyz0123456789";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}
?>
