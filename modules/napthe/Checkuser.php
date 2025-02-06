<?php
$rid=trim($_GET["id"]);
global $db,$prefix,$currentlang,$module_name,$userInfo;
$result = $db->sql_query("SELECT fullname, email FROM ".$prefix."_user WHERE fullname='$rid' OR email='$rid'");
if($db->sql_numrows($result) > 0) {
	list($fullname,$email) = $db->sql_fetchrow($result);
	if($email==$userInfo['email'])
		echo "Bạn không thể chuyển tiền cho tài khoản này!";
	else
		echo "Tài khoản tồn tại! bạn có thể thực hiện giao dịch.";
	
}
else
{
	echo "Tài khoản không tồn tại! Mời bạn nhập đúng tài khoản.";
}
?>