<?php
if (!defined('CMS_SYSTEM')) die();

if (defined('iS_USER') && isset()) header("Location: index.php");

$page_title = _USER_REGISTER;

include_once("header.php");
include_once("WebUser.class.php");

//$db->sql_query("DELETE FROM {$prefix}_user WHERE UNIX_TIMESTAMP(registrationTime) + ".strval($activationPeriod * 3600).' <= NOW() AND activationCode IS NOT NULL');

OpenTab(_USER_REGISTER);

$sAgreeChecked = $sMrSelected = $sMrsSelected = 0;
$sEmail = $sCEmail = $sAddress = $sPhone = $sName = '';

if (isset($_POST["submit_up"])) {
	//$captcha = new CAPTCHA(6);
	//if ($captcha->isValid($_POST['captcha'])) {
		$user = new WebUser($_POST['title'], $_POST['name'], $_POST['email'], $_POST['address'], $_POST['phone'], md5($_POST['password']));
		$ret = $user->register($_POST['url']);
		if ($ret == WEBUSER_EMAIL_REGISTERED) {
			$err_mess = _USER_EMAIL_REGISTERED;
		} elseif ($ret == WEBUSER_REGISTRATION_SUCCEEDED) {
			echo "<div align=\"center\">"._USER_REGISTRATION_SUCCESSFUL."</div>";
			echo "<meta http-equiv=\"refresh\" content=\"5;url= ".url_sid("index.php")."\">";
			CloseTab();
			include_once("footer.php");
			exit();
		} elseif ($ret == WEBUSER_REGISTRATION_FAILED) {
			$err_mess = _USER_ERROR_REGISTERING;
		}
	/*} else {
	/	if (isset($_POST['agree'])) $sAgreeChecked = 1;
		else $sAgreeChecked = 0;
		if ($_POST['title'] == '0') {
			$sMrSelected = 1;
			$sMrsSelected = 0;
		} else {
			$sMrSelected = 0;
			$sMrsSelected = 1;
		}
		$sEmail = $_POST['email'];
		$sCEmail = $_POST['cemail'];
		$sAddress = $_POST['address'];
		$sPhone = $_POST['phone'];
		$sName = $_POST['name'];
		$err_mess = _INCORRECT_CAPTCHA;
	}*/
}

echo "<script language=\"javascript\" type=\"text/javascript\">";
echo "function Check_Valid(f) {";
echo "var err = 0";
echo "if (fetch_object('agree').checked == false) {";
echo "alert('"._USER_ERROR_AGREE."')";
echo "return false";
echo "err = 1";
echo "}";
echo "if ((f.email.value == '') || (f.password.value == '') || (f.name.value == '') || (f.address.value == '') || (f.phone.value == '')) {";
echo "alert('"._USER_ERROR_INCOMPLETE."');";
echo "return false;";
echo "err = 1;";
echo "}";
echo "if (!isEmail(f.email.value)) {";
echo "alert('"._USER_ERROR_EMAIL_1."');";
echo "f.email.focus();";
echo "return false;";
echo "err = 1;";
echo "}";
echo "if (f.email.value != f.cemail.value) {";
echo "alert('"._USER_ERROR_EMAIL_2."');";
echo "f.email.focus();";
echo "return false;";
echo "err = 1;";
echo "}";
echo "if (f.password.value != f.cpassword.value) {";
echo "alert('"._USER_ERROR_PASSWORD."');";
echo "f.password.focus();";
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
echo "<tr><td><font size=\"2\">"._USER_EMAIL.": </font></td>";
echo '<td style="padding-left: 10px"><input type="text" id="email" name="email" value="'."$sEmail\" size=\"40\"></td></tr>";
echo "<tr><td><font size=\"2\">"._USER_CONFIRM_EMAIL.": </font></td>";
echo '<td style="padding-left: 10px"><input type="text" id="cemail" name="cemail" value="'."$sCEmail\" size=\"40\"></td></tr>";
echo "<tr><td><font size=\"2\">"._USER_PASSWORD.": </font></td>";
echo '<td style="padding-left: 10px"><input type="password" id="password" name="password" size="40"></td>'."</tr>";
echo "<tr><td><font size=\"2\">"._USER_CONFIRM_PASSWORD.": </font></td>";
echo '<td style="padding-left: 10px"><input type="password" id="cpassword" name="cpassword" size="40"></td>'."</tr>";
echo "<tr>";
echo "<td><font size=\"2\">"._USER_TITLE."</font></td>";
echo "<td style=\"padding-left: 10px\"><select name=\"title\">";
$mrSelected = $mrsSelected = '';
if ($sMrSelected) $mrSelected = ' selected="selected"';
elseif ($sMrsSelected) $mrsSelected = ' selected="selected"';
echo "<option value=\"0\"$mrSelected>"._USER_MR."</option>";
echo "<option value=\"1\"$mrsSelected>"._USER_MRS."</option>";
echo "</select></td>";
echo "</tr>";
echo "<tr><td><font size=\"2\">"._USER_FULLNAME.": </font></td>";
echo '<td style="padding-left: 10px"><input type="text" id="name" name="name" value="'."$sName\" size=\"40\"></td></tr>";
echo "<tr><td><font size=\"2\">"._USER_ADDRESS.": </font></td>";
echo '<td style="padding-left: 10px"><input type="text" id="address" name="address" value="'."$sAddress\" size=\"40\"></td></tr>";
echo "<tr><td><font size=\"2\">"._USER_PHONE.": </font></td>";
echo '<td style="padding-left: 10px"><input type="text" id="phone" name="phone" value="'."$sPhone\" size=\"40\"></td></tr>";
/*echo "<tr><td colspan=\"2\" align=\"center\">";
echo "<div align=\"center\"><img src=\"index.php?f=captcha\"></div>";
echo "</td></tr>";
echo "<tr><td colspan=\"2\">"._ENTER_CAPTCHA.": <input type=\"text\" name=\"captcha\" id=\"captcha\"></td></tr>";
if ($sAgreeChecked) $agreeChecked = ' checked="checked"';
else $agreeChecked = '';

echo "<tr><td colspan=\"2\"><input type=\"checkbox\" id=\"agree\" name=\"agree\" value=\"agree\"$agreeChecked><font size=\"2\">"._USER_AGREE."</font></td></tr>";
*/
echo '<input type="hidden" name="submit_up" value="1">';
echo "<script>var currentURL=encodeURI(location.href);";
echo "document.write('<input type=\"hidden\" name=\"url\" value=\"' + currentURL + '\">')</script>";
echo "<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"submit\" value=\""._USER_REGISTER_SHORT."\"></td></tr>";
echo "</table></form>";

CloseTab();
include_once("footer.php");
?>