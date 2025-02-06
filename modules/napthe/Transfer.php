<?php
if (!defined('CMS_SYSTEM')) die();
if (!defined('iS_USER') || !isset($userInfo)) header("Location: ".url_sid("index.php?f=user&do=login")."");
$page_title="Chuyển tiền";
global $db,$prefix,$currentlang,$module_name,$userInfo;
include("header.php");
$cryptinstall="captcha/cryptographp.fct.php";
include $cryptinstall; 
OpenTab("Chuyển tiền");
echo "<div class=\"content\">";
$txtemail = $txtmoney = $txtpassword = $err_money = $err_password = $err_name =  $err_email = $error_captcha= '';
if( isset($_POST['subup']) && $_POST['subup'] == 1) {
	$err = 0;
	$txtemail = trim(($_POST['txtemail']));
	$txtmoney = intval($_POST['txtmoney']);
	$txtpassword = md5($_POST['txtpassword']);
	//kiem tra tai khoan nhan co ton tai
	if (empty($txtemail)) {
		$err_email = "<font color=\"red\"> Mời bạn nhập tài khoản!</font>";
		$err = 1;
	}
	//kiem tra tien nhap vao
	if (empty($txtmoney) || !is_number($txtmoney)) {
		$err_money = "<font color=\"red\"> Số tiền bạn nhập không đúng!</font>";
		$err = 1;
	}
	//kiem tra so du tai khoan
	if ($txtmoney > $userInfo['money']) {
		$err_money = "<font color=\"red\"> Số tiền bạn nhập vượt quá giới hạn tài khoản!</font>";
		$err = 1;
	}
	//kiem tra so du tai khoan
	if ($txtpassword != $userInfo['pass']) {
		$err_password = "<font color=\"red\">Mật khẩu không đúng!</font>";
		$err = 1;
	}
	//kiem tra tai khoan email người nhận
	//$txtpassword=md5($txtpassword);
	$result = $db->sql_query("SELECT id,fullname,email,money FROM ".$prefix."_user WHERE fullname='$txtemail' OR email='$txtemail'");
	if($db->sql_numrows($result) > 0) {
		list($ck_id,$ck_fullname, $ck_email, $ck_money) = $db->sql_fetchrow($result);
		if($ck_email==$userInfo['email']){
			$err_email = "<font color=\"red\"> Tài khoản không hợp lệ!</font>";
			$err = 1;
		}
	}
	else{
		$err_email = "<font color=\"red\"> Tài khoản không hợp lệ!</font>";
		$err = 1;
	}
	if (!chk_crypt($_POST['captcha'])) 
	{
		$err = 1;
		$error_captcha = "<font  color=\"red\">Mã bảo xác nhận không đúng!</font>";
	}
	if (!$err) {	
		//tru tien tai khoan gui
		$money_old =$userInfo['money'];
		$money=$money_old-$txtmoney;
		$db->sql_query("UPDATE {$prefix}_user SET money=$money WHERE email='".$userInfo['email']."'");
		updateuserlog($userInfo['id'],"Chuyển tiền",$money_old, $txtmoney, $money,"-","Chuyển tiền cho ".$ck_fullname."");
		//cong tien cho tai khoan nhan
		$money=$ck_money+$txtmoney;
		$db->sql_query("UPDATE {$prefix}_user SET money=$money WHERE email='".$ck_email."'");
		updateuserlog($ck_id,"Nhận tiền ",$ck_money, $txtmoney, $money,"+","Nhận tiền từ ".$userInfo['fullname']."");
		echo "<script language=\"javascript\" type=\"text/javascript\">";
		echo "alert('Chuyển tiền thành công!');";
		echo "window.location.href=\"index.php?f=".$module_name."&do=transfer\"";
		echo "</script>";
	}
	else{
		$txtpassword="";
	}
}


echo "<form autocomplete=\"on\"  action=\"index.php?f=$module_name&do=$do\" method=\"POST\">";
echo "<table class=\"tableborder\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\" width=\"100%\">\n";
echo "<tr>\n";
echo "<td width=\"80px\" class=\"row1\">Tài khoản nhận:</td>\n";
echo "<td class=\"row1\"><input type=\"text\" id=\"txtemail\" name=\"txtemail\" onblur=\" show_ajaxcontent_byid( this.value, 'napthe', 'checkuser', 'id', 'checkuser')\"  value=\"$txtemail\" size=\"30\">$err_email<span id=\"checkuser\"></span></td>";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"80px\" class=\"row1\">Số tiền:</td>\n";
echo "<td width=\"80px\" class=\"row1\"><input type=\"text\" id=\"txtmoney\" name=\"txtmoney\" value=\"$txtmoney\" size=\"30\">$err_money</td>\n";
echo "</td></tr>\n";
echo "<tr>\n";
echo "<td class=\"row1\">Mật khẩu:</td>\n";
echo "<td class=\"row1\"><input  type=\"password\" id=\"txtpassword\"value=\"$txtpassword\" name=\"txtpassword\"  size=\"30\">$err_password</td>\n";
echo "</tr>\n";
?>
<tr>
<td class="row1">Mã xác nhận:<span class="risk">*</span></td>
<td class="row1"><input type="text" name="captcha"  id="captcha" size="10">
<?php dsp_crypt(0,1); ?><?echo $error_captcha?>
</td>
</tr>
<?php
echo "<tr><td>&nbsp;</td><td><input type=\"hidden\" name=\"subup\" value=\"1\"><input class=\"sb_but1\" type=\"submit\" name=\"submit\" value=\"Chuyển tiền\" ></td></tr>";
echo "</table>";

	
echo"</form>";
$result = $db->sql_query("SELECT content FROM ".$prefix."_gentext WHERE textname='transfer' AND alanguage='$currentlang'");
list($content) = $db->sql_fetchrow($result);
?>
<div><?php echo $content?></div>
<?php
echo "</div>";
CloseTab();
include("footer.php");
?>
