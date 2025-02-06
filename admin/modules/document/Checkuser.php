<?php
$rid=trim($_GET["id"]);
global $db,$prefix,$currentlang,$module_name,$userInfo;
if($rid!="*"){
	$result = $db->sql_query("SELECT fullname, email FROM ".$prefix."_user WHERE fullname='$rid' OR email='$rid'");
	if($db->sql_numrows($result) > 0) {
		echo "Tài khoản tồn tại!";
	}
	else
	{
		echo "Tài khoản không tồn tại!";
	}
}
?>