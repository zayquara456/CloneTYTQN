<?php
if (!defined('CMS_SYSTEM')) die();

//if (!defined('iS_USER') || !isset($userInfo)) header("Location: index.php?f=user&do=login");

$page_title = "Xác minh tài khoản";

include_once('header.php');
require_once('WebUser.class.php');

OpenTab(_USER_EDIT_PROFILE);

//$user = new WebUser($userInfo['id']);

if (isset($_POST["submit_up"])){
	$password =md5($_POST['password']);
	$fullname = $escape_mysql_string($_POST['name']);
	$phone  = $escape_mysql_string($_POST['phone']);
	$result = $db->sql_query("SELECT fullname FROM ".$prefix."_user WHERE fullname='$name'");
	$err = 0;
	if($db->sql_numrows($result) < 1) {
		$err = 1;
		$err_mess = "<font  color=\"red\">Tài khoản không tồn tại!</font>";
	}
	$result = $db->sql_query("SELECT pass FROM ".$prefix."_user WHERE pass='$password'");
	if($db->sql_numrows($result) < 1) {
		$err = 1;
		$err_mess = "<font  color=\"red\">Mật khẩu không tồn tại!</font>";
	}
	if(!$err) {
		
		$db->sql_query("UPDATE ".$prefix."_user SET phone='$phone' WHERE fullname='$fullname'");
		$err_mess=_USER_PROFILE_UPDATED;
	}
	//else $err_mess = _USER_ERROR_UPDATING_PROFILE;
}

echo "<script language=\"javascript\" type=\"text/javascript\">";
echo "function Check_Valid(f) {";
echo "var err = 0";
echo "var Password = fetch_object('password');";
echo "var CPassword = fetch_object('cpassword');";
echo "var Name = fetch_object('name');";
echo "var Address = fetch_object('address');";
echo "var Phone = fetch_object('phone');";
echo "if (Address.value == '') {";
echo "alert('"._USER_ERROR_INCOMPLETE."');";
echo "Address.focus();";
echo "return false;";
echo "err = 1;";
echo "}";
echo "if (Phone.value == '') {";
echo "alert('"._USER_ERROR_INCOMPLETE."');";
echo "Phone.focus();";
echo "return false;";
echo "err = 1;";
echo "}";
echo "if (Name.value == '') {";
echo "alert('"._USER_ERROR_INCOMPLETE."');";
echo "Name.focus();";
echo "return false;";
echo "err = 1;";
echo "}";
echo "if (Password.value != CPassword.value) {";
echo "alert('"._USER_ERROR_PASSWORD."');";
echo "Password.focus();";
echo "return false;";
echo "err = 1;";
echo "}";
echo "if(!err) {";
echo "if(f.submit) f.submit.disabled = true; }";
echo "return true; ";
echo "}";
echo "</script>";
echo '<div class="content">';
echo "<form method=\"POST\" action=\"".url_sid("index.php?f=$module_name&do=$do")."\" onsubmit=\"return Check_Valid(this);\">";
if (isset($err_mess)) {
	echo "<div align=\"center\"><font color=\"red\"><b>$err_mess</b></font></div>";
}
echo "<table border=\"0\" align=\"center\">";
echo "<tr><td colspan=\"2\">Mời bạn cập nhật thông tin để xác minh tài khoản.<br>Bước 1.Cập nhật thông tin tài khoản</td></tr>";
echo "<tr><td><font size=\"2\">"._USER_FULLNAME.": </font></td>";
echo '<td style="padding-left: 10px"><input type="text" id="name" value="" name="name" size="40"></td>'."</tr>";
echo "<tr><td height=\"24\"><font size=\"2\">"._USER_PASSWORD.": </font></td>";
echo '<td style="padding-left: 10px"><input type="password" id="password" name="password" size="40"></td>'."</tr>";
echo "<tr><td><font size=\"2\">"._USER_PHONE.": </font></td>";
echo '<td style="padding-left: 10px"><input type="text" id="phone" value="" name="phone" size="40"></td>'."</tr>";
echo "<tr><td height=\"24\" colspan=\"2\" align=\"center\"><input type=\"submit\" class=\"sb_but1\" name=\"submit_up\" value=\""._SAVECHANGES."\"></td></tr>";
echo "<tr><td colspan=\"2\"><br>Bước 2. Soạn tin nhắn ON XMXD gửi 8085 (Phí tin nhắn 500đ/1 tin)</td></tr>";
echo "</table></form></div>";

CloseTab();
include_once('footer.php');
?>