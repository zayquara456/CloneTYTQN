<?php
$rid=trim($_GET["id"]);
global $db,$prefix,$currentlang,$module_name,$userInfo;
if($rid!="*"){
	$result = $db->sql_query("SELECT fullname, email, money, phone, address FROM ".$prefix."_user WHERE fullname='$rid' OR email='$rid'");
	if($db->sql_numrows($result) > 0) {
		list($fullname, $email, $money, $phone,$address) = $db->sql_fetchrow($result);
		echo "<br>Số tiền trong tài khoản: ".bsVndDot($money)."";
	}
	else
	{
		echo "Tài khoản không tồn tại!";
	}
}
?>