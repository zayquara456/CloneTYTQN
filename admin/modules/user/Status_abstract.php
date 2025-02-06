<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$id = intval($_GET['id']);
$status = intval($_GET['status']);
$result = $db->sql_query("SELECT userid, money, code, status FROM ".$prefix."_transfer_bank WHERE id='$id'");
if(!empty($id) || $db->sql_numrows($result) == 1){
	list($userid, $money, $code, $s_status)= $db->sql_fetchrow($result);
}
switch ($status){
		case 1:
			//tru tien tai khoan gui
			$result = $db->sql_query("SELECT money FROM ".$prefix."_user WHERE id='$userid'");
			if($db->sql_numrows($result) == 1){
				list($moneyold)= $db->sql_fetchrow($result);
				$moneynew=$moneyold - $money;
				$db->sql_query("UPDATE {$prefix}_user SET money=$moneynew WHERE id=$userid");
				$db->sql_query("UPDATE ".$prefix."_transfer_bank SET status=1 WHERE id='$id'");
				updateadmlog($admin_ar[0], $adm_modname, "Quản lý rút tiền vào ngân hàng", "Chuyển giao dịch sang trạng thái chờ xử lý");
			}
			break;
		case 2:
			$db->sql_query("UPDATE ".$prefix."_transfer_bank SET status=2 WHERE id='$id'");
			updateadmlog($admin_ar[0], $adm_modname,"Quản lý rút tiền vào ngân hàng","Chuyển giao dịch sang trạng thái đã xử lý");
			updateuserlog($userid,"Rút tiền",$money,"-","Rút tiền vào ngân hàng tài khoản ".CutString($code,8)."(Giao dịch đã xử lý thành công)");
			break;
		case 3:
			$result = $db->sql_query("SELECT money FROM ".$prefix."_user WHERE id='$userid'");
			if($db->sql_numrows($result) == 1){
				list($moneyold)= $db->sql_fetchrow($result);
				$moneynew=$moneyold + $money;
				$db->sql_query("UPDATE {$prefix}_user SET money=$moneynew WHERE id=$userid");
				$db->sql_query("UPDATE ".$prefix."_transfer_bank SET status=3 WHERE id='$id'");
				updateadmlog($admin_ar[0], $adm_modname,"Quản lý rút tiền vào ngân hàng","Chuyển giao dịch sang trạng thái đã hủy");
			updateuserlog($userid,"Rút tiền",$money,"+","Rút tiền vào ngân hàng tài khoản ".CutString($code,8)."(Giao dịch đã hủy)");
			}
			
			
			break;
	}
header("Location: modules.php?f=".$adm_modname."&do=abstract");

?>