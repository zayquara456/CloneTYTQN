<?php
if (!defined('CMS_SYSTEM')) die();

if (defined('iS_USER') && isset($userInfo)) header("Location: index.php");

$page_title = _USER_REGISTER;

include_once("header.php");
include_once("WebUser.class.php");
$cryptinstall="captcha/cryptographp.fct.php";
include $cryptinstall; 
//$db->sql_query("DELETE FROM {$prefix}_user WHERE UNIX_TIMESTAMP(registrationTime) + ".strval($activationPeriod * 3600).' <= NOW() AND activationCode IS NOT NULL');

OpenTab(_USER_REGISTER);

$sAgreeChecked = $sMrSelected = $sMrsSelected = 0;
$sEmail = $sCEmail = $sAddress = $sPhone = $sName = $error_name = $error_captcha =$error_email='';

if (isset($_POST["submit_up"])) {
//$captcha = new CAPTCHA(6);
	//if ($captcha->isValid($_POST['captcha'])) {
	$title		= $_POST['title'];
	$name		= trim($_POST['name']);
	$email		= $_POST['email'];
	$cemail		= $_POST['cemail'];
	$address	= $_POST['address'];
	$phone		= $_POST['phone'];
	$password	= $_POST['password'];
	$cpassword	= $_POST['cpassword'];
	if(empty($name))
	{
		$err = 1;
		$error_name = "<font  color=\"red\">Mời bạn nhập tài khoàn!</font>";
	}
	elseif(strlen($name)<5 )
	{
		$err = 1;
		$error_name = "<font  color=\"red\">Tài khoản phải lớn hơn 6 ký tự!</font>";
	}
	$result = $db->sql_query("SELECT fullname FROM ".$prefix."_user WHERE fullname='$name'");
	if($db->sql_numrows($result) > 0) {
		$err = 1;
		$error_name = "<font  color=\"red\">Tài khoản đã tồn tại!</font>";
	}
	if(empty($email)){
		$err = 1;
		$error_email = "<font  color=\"red\">Mời bạn nhập email!</font>";
	}
	if(empty($cemail)){
		$err = 1;
		$error_cemail = "<font  color=\"red\">Mời bạn nhập xác nhận email!</font>";
	}
	if($email != $cemail){
		$err = 1;
		$error_cemail = "<font  color=\"red\">Nhập lại email không hợp lệ!</font>";
	}
	if(empty($password)){
		$err = 1;
		$error_password = "<font  color=\"red\">Mời bạn nhập mật khẩu!</font>";
	}
	if(empty($cpassword)){
		$err = 1;
		$error_cpassword = "<font  color=\"red\">Mời bạn nhập xác nhận mật khẩu!</font>";
	}
	if($cpassword != $password){
		$err = 1;
		$error_cpassword = "<font  color=\"red\">Xác nhận mật khẩu không hợp lệ!</font>";
	}
	$result = $db->sql_query("SELECT email FROM ".$prefix."_user WHERE email='$email'");
	if($db->sql_numrows($result) > 0) {
		$err = 1;
		$error_email = "<font  color=\"red\">Email đã tồn tại!</font>";
	}
	if(empty($address)){
		$err = 1;
		$error_address = "<font  color=\"red\">Mời bạn nhập địa chỉ!</font>";
	}
	if(empty($phone)){
		$err = 1;
		$error_phone = "<font  color=\"red\">Mời bạn nhập số điện thoại!</font>";
	}
	if (!chk_crypt($_POST['captcha'])) 
	{
		$err = 1;
		$error_captcha = "<font  color=\"red\">Mã bảo xác nhận không đúng!</font>";
	}
	if (!$err) {
		$user = new WebUser($title, $name, $email, $address, $phone, md5($password));
		$ret = $user->register($_POST['url']);
		if ($ret == WEBUSER_EMAIL_REGISTERED) {
			$err_mess = _USER_EMAIL_REGISTERED."";
		} elseif ($ret == WEBUSER_REGISTRATION_SUCCEEDED) {
			echo "<div class=\"content\" style=\"height:300px; padding-top:50px\" align=\"center\"><b>"._USER_REGISTRATION_SUCCESSFUL."</b><p><b> Mời bạn kiểm tra email <b>".$email."</b> để kích hoạt tài khoản!<br><i>Nếu yêu cầu kích hoạt tài khoản của acud.vn không có trong Hộp thư đến (Inbox), vui lòng kiểm tra trong mục Thư rác (Spam).</i></b></p></div>";
			//echo "<meta http-equiv=\"refresh\" content=\"15;url= ".url_sid("index.php")."\">";
			CloseTab();
			include_once("footer.php");
			exit();
		} elseif ($ret == WEBUSER_REGISTRATION_FAILED) {
			$err_mess = _USER_ERROR_REGISTERING;
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
		}
	}
	else {
		//if (isset($_POST['agree'])) $sAgreeChecked = 1;
		//else $sAgreeChecked = 0;
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
		//$err_mess = _INCORRECT_CAPTCHA;
	}
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
echo "<div class=\"content\">";
echo "<form autocomplete=\"off\" method=\"POST\" action=\"".url_sid("index.php?f=$module_name&do=$do")."\" onsubmit=\"return Check_Valid(this);\">";
if (isset($err_mess)) {
	echo "<div align=\"center\"><font color=\"red\"><b>$err_mess</b></font></div>";
}
echo "<table border=\"0\" align=\"center\">";
echo "<tr><td><font size=\"2\">"._USER_FULLNAME.":<span class=\"risk\">*</span> </font></td>";
echo '<td style="padding-left: 10px"><input type="text" id="name" name="name" value="'."$sName\" size=\"40\"> $error_name</td></tr>";
echo "<tr><td><font size=\"2\">"._USER_EMAIL.":<span class=\"risk\">*</span> </font></td>";
echo '<td style="padding-left: 10px"><input type="text" id="email" name="email" value="'."$sEmail\" size=\"40\"> $error_email</td></tr>";
echo "<tr><td><font size=\"2\">"._USER_CONFIRM_EMAIL.":<span class=\"risk\">*</span> </font></td>";
echo '<td style="padding-left: 10px"><input type="text" id="cemail" name="cemail" value="'."$sCEmail\" size=\"40\"> $error_cemail</td></tr>";
echo "<tr><td><font size=\"2\">"._USER_PASSWORD.":<span class=\"risk\">*</span> </font></td>";
echo '<td style="padding-left: 10px"><input type="password" id="password" name="password" size="40"> '.$error_password.'</td>'."</tr>";
echo "<tr><td><font size=\"2\">"._USER_CONFIRM_PASSWORD.":<span class=\"risk\">*</span> </font></td>";
echo '<td style="padding-left: 10px"><input type="password" id="cpassword" name="cpassword" size="40"> '.$error_cpassword.'</td>'."</tr>";
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

echo "<tr><td><font size=\"2\">"._USER_ADDRESS.":<span class=\"risk\">*</span> </font></td>";
echo '<td style="padding-left: 10px"><input type="text" id="address" name="address" value="'."$sAddress\" size=\"40\"> $error_address</td></tr>";
echo "<tr><td><font size=\"2\">"._USER_PHONE.":<span class=\"risk\">*</span> </font></td>";
echo '<td style="padding-left: 10px"><input type="text" id="phone" name="phone" value="'."$sPhone\" size=\"40\"> $error_phone</td></tr>";
?>
<tr>
<td >Mã xác nhận:<span class="risk">*</span></td>
<td  style="padding-left: 10px"><input type="text" name="captcha"  id="captcha" size="10">
<?php dsp_crypt(0,1); ?> <?echo $error_captcha?>
</td>
</tr>
<?php
if ($sAgreeChecked) $agreeChecked = ' checked="checked"';
else $agreeChecked = '';
echo "<tr><td colspan=\"2\"><input type=\"checkbox\" id=\"agree\" name=\"agree\" value=\"agree\"$agreeChecked><font size=\"2\">"._USER_AGREE."</font></td></tr>";
echo '<input type="hidden" name="submit_up" value="1">';
echo "<input type=\"hidden\" name=\"url\" value=\"$urlsite\">";
echo "<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"submit\" class=\"sb_but1\" value=\""._USER_REGISTER_SHORT."\"></td></tr>";
echo "</table></form></div>";

CloseTab();
include_once("footer.php");
?>