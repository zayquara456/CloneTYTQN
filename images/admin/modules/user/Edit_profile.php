<?php
if (!defined('CMS_SYSTEM')) die();

$page_title = _USER_EDIT_PROFILE;

include_once('header.php');
require_once('WebUser.class.php');

OpenTab(_USER_EDIT_PROFILE);

$user = new WebUser($userInfo['id']);

if (isset($_POST["submit_up"])) {
	$captcha = new CAPTCHA(6);
	if ($captcha->isValid($_POST['captcha'])) {
		$user->setSex($_POST['title']);
		$user->setName($_POST['name']);
		$user->setAddress($_POST['address']);
		$user->setPhone($_POST['phone']);
		if (!empty($_POST['password']) && !empty($_POST['cpassword'])) {
			$user->setPassword(md5($_POST['password']));
		}
		$ret = $user->update();
		if ($ret) $err_mess = _USER_PROFILE_UPDATED;
		else $err_mess = _USER_ERROR_UPDATING_PROFILE;
	} else {
		$err_mess = _INCORRECT_CAPTCHA;
	}
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

echo "<form method=\"POST\" action=\"".url_sid("index.php?f=$module_name&do=$do")."\" onsubmit=\"return Check_Valid(this);\">";
if (isset($err_mess)) {
	echo "<div align=\"center\"><font color=\"red\"><b>$err_mess</b></font></div>";
}
echo "<table border=\"0\" align=\"center\">";
echo "<tr><td height=\"24\"><font size=\"2\">"._USER_PASSWORD.": </font></td>";
echo '<td style="padding-left: 10px"><input type="password" id="password" name="password" size="40"></td>'."</tr>";
echo "<tr><td height=\"24\"><font size=\"2\">"._USER_CONFIRM_PASSWORD.": </font></td>";
echo '<td style="padding-left: 10px"><input type="password" id="cpassword" name="cpassword" size="40"><br /><font size="1">&nbsp;&nbsp;* '._USER_LEAVE_BLANK_TO_KEEP_AS_IS.'</font></td>'."</tr>";
echo "<tr><td height=\"24\" colspan=\"2\"><hr /></td>";
echo "<tr><td><font size=\"2\">"._USER_FULLNAME.": </font></td>";
echo '<td style="padding-left: 10px"><input type="text" id="name" value="'.$user->name.'" name="name" size="40"></td>'."</tr>";
echo "<tr><td><font size=\"2\">"._USER_ADDRESS.": </font></td>";
echo '<td style="padding-left: 10px"><input type="text" id="address" value="'.$user->address.'" name="address" size="40"></td>'."</tr>";
echo "<tr><td><font size=\"2\">"._USER_PHONE.": </font></td>";
echo '<td style="padding-left: 10px"><input type="text" id="phone" value="'.$user->phone.'" name="phone" size="40"></td>'."</tr>";
echo "<tr><td colspan=\"2\" align=\"center\">";
echo "<div align=\"center\"><img src=\"index.php?f=captcha\"></div>";
echo "</td></tr>";
echo "<tr><td colspan=\"2\">"._ENTER_CAPTCHA.": <input type=\"text\" name=\"captcha\" id=\"captcha\"></td></tr>";
echo '<input type="hidden" name="submit_up" value="1">';
echo "<tr><td height=\"24\" colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"submit\" value=\""._SAVECHANGES."\"></td></tr>";
echo "</table></form>";

CloseTab();
include_once('footer.php');
?>