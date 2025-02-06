<?php
if (!defined('CMS_SYSTEM')) die();

if (!defined('iS_USER') || !isset($userInfo)) header("Location: index.php?f=user&do=login");

$page_title = _USER_EDIT_PROFILE;

include_once('header.php');
require_once('WebUser.class.php');

OpenTab(_USER_EDIT_PROFILE);

$user = new WebUser($userInfo['id']);

if (isset($_POST["submit_up"])){
	$user->setSex($_POST['title']);
	//$user->setName($_POST['name']);
	$user->setAddress($_POST['address']);
	$user->setPhone($_POST['phone']);
	if (!empty($_POST['password']) && !empty($_POST['cpassword'])) {
		$user->setPassword(md5($_POST['password']));
	}
	$ret = $user->update();
	if ($ret) $err_mess = _USER_PROFILE_UPDATED;
	else $err_mess = _USER_ERROR_UPDATING_PROFILE;
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
echo "<tr><td><font size=\"2\">"._USER_FULLNAME.": </font></td>";
echo '<td style="padding-left: 10px"><input type="text" id="name" value="'.$user->name.'" disabled=\"disabled\" name="name" size="40"></td>'."</tr>";
echo "<tr><td height=\"24\"><font size=\"2\">"._USER_PASSWORD.": </font></td>";
echo '<td style="padding-left: 10px"><input type="password" id="password" name="password" size="40"></td>'."</tr>";
echo "<tr><td height=\"24\"><font size=\"2\">"._USER_CONFIRM_PASSWORD.": </font></td>";
echo '<td style="padding-left: 10px"><input type="password" id="cpassword" name="cpassword" size="40"><font size="1">&nbsp;&nbsp;* '._USER_LEAVE_BLANK_TO_KEEP_AS_IS.'</font></td>'."</tr>";

echo "<tr>";
echo "<td><font size=\"2\">"._USER_TITLE."</font></td>";
echo "<td style=\"padding-left: 10px\"><select name=\"title\">";
$selected0 = '';
$selected1 = '';
if ($user->title == 0) $selected0 = ' selected="selected"';
elseif ($user->title == 1) $selected1 = ' selected="selected"';
echo "<option value=\"0\"$selected0>"._USER_MR."</option>";
echo "<option value=\"1\"$selected1>"._USER_MRS."</option>";
echo "</select></td>";
echo "</tr>";
echo "<tr><td><font size=\"2\">"._USER_ADDRESS.": </font></td>";
echo '<td style="padding-left: 10px"><input type="text" id="address" value="'.$user->address.'" name="address" size="40"></td>'."</tr>";
echo "<tr><td><font size=\"2\">"._USER_PHONE.": </font></td>";
echo '<td style="padding-left: 10px"><input type="text" id="phone" value="'.$user->phone.'" name="phone" size="40"></td>'."</tr>";
echo "<tr><td height=\"24\" colspan=\"2\" align=\"center\"><input type=\"submit\" class=\"sb_but1\" name=\"submit_up\" value=\""._SAVECHANGES."\"></td></tr>";
echo "</table></form></div>";

CloseTab();
include_once('footer.php');
?>