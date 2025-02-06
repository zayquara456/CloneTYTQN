<?php
if (!defined('CMS_SYSTEM')) die();
global $urlsite;
$t = isset($_GET['t']) ? $_GET['t'] : "";
$t=$escape_mysql_string($t);
//title
$resultmodule = $db->sql_query("SELECT mid, custom_title, seo_title, seo_description, seo_keyword FROM {$prefix}_modules WHERE active=1 AND alanguage='$currentlang' AND title='$module_name'");
if($db->sql_numrows($resultmodule) > 0) 
{
	list($mmid, $mcustom_title, $mseo_title, $mseo_description, $mseo_keyword) = $db->sql_fetchrow($resultmodule);
	if($mseo_title!="")
		$page_title = "$mseo_title";
	else
		$page_title .= "$mcustom_title";
	if($mseo_keyword!="")
	$keywords_site =$mseo_keyword;
	//description
	if($mseo_description!="") 
		$description_site =$mseo_description;
}
include_once("header.php");
OpenTab("Gửi yêu cầu");

if(isset($_POST['submit_up']) && $_POST['submit_up'] == 1) {
	// if( $_SESSION['security_code'] == $_POST['security_code'] && !empty($_SESSION['security_code'] ) ) {
		// Insert you code for processing the form here, e.g emailing the submission, entering it into a database. 
	//	echo 'Thank you. Your message said "'.$_POST['message'].'"';
		
  

	$ctname = $escape_mysql_string(trim($_POST['ctname']));
	$appell = intval($_POST['appell']);
	$address = $escape_mysql_string(trim($_POST['address']));
	$phone = intval($_POST['phone']);
	$email = $escape_mysql_string(trim($_POST['email_contact']));
	$part = intval($_POST['part']);
	$title = $escape_mysql_string(trim($_POST['title']));
	$message = $escape_mysql_string(trim($_POST['message']));

	if(!is_email($email) || empty($message)) {
		$err_mess = "<font color=\"red\">"._ERROR_CT."</font><br/>";
		$err = 1;
	}

	if(!isset($err)) {
		if($part != 0) {
			list($pid_name, $partmail) = $db->sql_fetchrow($db->sql_query("SELECT title, email FROM ".$prefix."_contact_part WHERE id='$part'"));
		} else {
			$pid_name = "Webmaster";
			$partmail = $adminmail;
		}
		$db->sql_query("INSERT INTO {$prefix}_contact (pid, pid_name, title, content, ctname, appell, address, phone, email, time, alanguage, response, onHome) VALUES ('$part', '$pid_name', '$title', '$message', '$ctname', '$appell', '$address', '$phone', '$email', '".time()."', '$currentlang', '', 0)");

		$app_ar = array(_MALE,_FEMALE);
		$headers  = "";
		$headers .= "<html xmlns=\"http://www.w3.org/1999/xhtml\">";
		$headers .= "<head>";
		$headers .= "	<meta http-equiv=\"Content-Type\" content=\"text/html; charset="._CHARSET."\" />";
		$headers .= "<link rel=\"StyleSheet\" href=\"$siteurl/css/".$Default_Temp.".css\" type=\"text/css\">";
		$headers .= "</head><body bgcolor=\"#FFFFFF\" topmargin=\"20\" leftmargin=\"20\" rightmargin=\"20\" bottommargin=\"20\">";
		$headers .= "<div style=\"padding-bottom: 3px\"><b>"._CTNAME."</b>: {$app_ar[$appell]} ".stripslashes($ctname)." ".stripslashes($email)."</div>";
		$headers .= "<div style=\"padding-bottom: 3px\"><b>"._ADDRESS."</b>: ".stripslashes($address)."</div>";
		$headers .= "<div style=\"padding-bottom: 3px\"><b>"._PHONE."</b>: $phone</div>";
		$headers .= "<div style=\"padding-bottom: 3px\"><b>"._TOPART."</b>: $pid_name</div>";
		$headers .= "<div style=\"padding-bottom: 3px\"><b>"._TITLE."</b>: ".stripslashes($title)."</div><hr>";
		$headers .= "<div style=\"padding-bottom: 3px\"><b>"._CONTENT."</b>: ".stripslashes($message)."</div><hr>";
		$headers .= "</body></html>";

		$subject = "$sitename - "._MAIL_SUBJECT;
		//send mail support smtp
		sendmail($subject, $partmail, $email, $headers);
		//send mail not support smtp
		//mail($email, $subject, $headers);
		
		echo "<center>"._SENT."</center>";
		echo "<META HTTP-EQUIV=\"refresh\" content=\"5;URL= ".url_sid("index.php")."\">";
		CloseTab();
		include_once("footer.php");
		exit();
	}
	//unset($_SESSION['security_code']);
	// } else {
		// Insert your code for showing an error message here
	//	echo 'Sorry, you have provided an invalid security code';
   //}
}
else {
	$err_mess = $ctname = $address = $phone = $email = $title = $message = "";
}

$mrSelected = $mrsSelected = '';
if (defined('iS_USER') && isset($userInfo)) {
	if ($userInfo['title'] == '0') $mrSelected = ' selected="selected"';
	elseif ($userInfo['title'] == '1') $mrsSelected = ' selected="selected"';
	$ctname = $userInfo['fullname'];
	$address = $userInfo['address'];
	$phone = $userInfo['phone'];
	$email = $userInfo['email'];
}

$resultadd = $db->sql_query("SELECT address FROM ".$prefix."_contact_add WHERE address!='' AND alanguage='$currentlang'");
echo "<script language=\"javascript\" type=\"text/javascript\">";
echo "function Check_Valid(f) {";
echo "var Email = document.getElementById('email_contact');";
echo "var Mess = document.getElementById('message');";
echo "var err = 0;";
echo "if (!isEmail(Email.value)) {";
echo "alert('"._ERROR4."');";
echo "Email.focus();";
echo "return false;";
echo "err = 1;";
echo "}	";
echo "if (isEmpty(Mess.value)) {";
echo "alert('"._ERROR6."');";
echo "Mess.focus();";
echo "return false;";
echo "err = 1;";
echo "}	";
echo "if(!err) {";
echo "if(f.submit) f.submit.disabled = true; }";
echo "return true; ";
echo "}";
echo "</script>";
echo "<div class=\"content\">";
####################

echo "<form action=\"".url_sid("index.php?f=$module_name")."\" method=\"POST\"  enctype=\"application/x-www-form-urlencoded\" onsubmit=\"return Check_Valid(this);\">";
if($err_mess) {
	echo "<tr><td align=\"center\" colspan=\"2\"><b>$err_mess</b></td></tr>";
}
echo "<table border=\"0\" align=\"center\">";
echo "<tr><td  colspan=\"2\"><strong>Bạn vui lòng liên hệ với ban quản trị Thư viện xây dựng qua form liên hệ dưới đây để có được tài liệu $t bạn cần.<br>Sau khi nhận được yêu cầu chúng tôi sẽ trả lời bạn trong thời gian sớm nhất.<br>Xin cảm ơn!<p></strong></td></tr>";
echo "<tr>";
echo "<td height=\"24\"><font size=\"2\">"._APPELLATION."</font></td>";
echo "<td style=\"padding-left: 10px\"><select name=\"appell\">";
echo "<option value=\"0\"$mrSelected>"._MALE."</option>";
echo "<option value=\"1\"$mrsSelected>"._FEMALE."</option>";
echo "</select></td>";
echo "</tr>";
echo "<tr><td  height=\"24\"><font size=\"2\">"._FULLNAME."</font></td><td style=\"padding-left: 10px\"><input name=\"ctname\" value=\"$ctname\" id=\"ctname\" maxlength=\"150\" size=\"40\" type=\"text\"></td></tr>";
echo "<tr><td  height=\"24\"><font size=\"2\">"._ADDRESS."</font></td><td style=\"padding-left: 10px\"><input name=\"address\" value=\"$address\" id=\"address\" maxlength=\"250\" size=\"40\" type=\"text\"></td></tr>";
echo "<tr><td  height=\"24\"><font size=\"2\">"._PHONE."</font></td><td style=\"padding-left: 10px\"><input name=\"phone\" value=\"$phone\" id=\"phone\" maxlength=\"50\" size=\"40\" type=\"text\"></td></tr>";
echo "<tr><td height=\"24\"><font size=\"2\">"._EMAIL."</font></td><td style=\"padding-left: 10px\"><input name=\"email_contact\" value=\"$email\" id=\"email_contact\" maxlength=\"150\" size=\"40\" type=\"text\">&nbsp;<font size=\"1\" color=\"red\">*</font></td></tr>";
echo "<tr><td height=\"24\"><font size=\"2\">"._TOPART."</font></td>";
echo "<td style=\"padding-left: 10px\"><select name=\"part\">";
echo "<option value=\"0\">Webmaster</option>";
$result_part = $db->sql_query("SELECT id, title FROM ".$prefix."_contact_part WHERE alanguage='$currentlang' ORDER BY title");
while(list($idp, $titlep) = $db->sql_fetchrow($result_part))
{
	if($idp==5)
		echo "<option value=\"$idp\" selected>$titlep</option>";
	else
		echo "<option value=\"$idp\">$titlep</option>";
}
echo "</select>";
echo "</td></tr>";
echo '<tr><td colspan="3"><hr /></td></tr>';
echo "<tr><td><font size=\"2\">"._TITLE."</font></td><td style=\"padding-left: 10px\"><input name=\"title\" value=\"$t\" id=\"title\" maxlength=\"150\" size=\"40\" type=\"text\"></td></tr>";
echo "<tr><td height=\"24\"><font size=\"2\">"._CONTENT."</font></td>";
echo "<td style=\"padding-left: 10px\"><textarea name=\"message\" id=\"message\" rows=\"12\" cols=\"40\" wrap=\"virtual\">$message</textarea></td></tr>";
//////////////
/*echo "<tr><td colspan=\"3\"><hr /></td></tr>";
echo "<tr><td colspan=\"3\" align=\"center\">";
echo "<div align=\"center\"><img src=\"index.php?f=captcha\"></div>";
echo "</td></tr>";
echo "<tr><td colspan=\"3\">"._ENTER_CAPTCHA.": <input type=\"text\" name=\"captcha\" id=\"captcha\">&nbsp;"._REDDOT."</td></tr>";
echo '<input type="hidden" name="submit_up" value="1">';
echo "<script>var currentURL=encodeURI(location.href);";
echo "document.write('<input type=\"hidden\" name=\"url\" value=\"' + currentURL + '\">')</script>";
echo "<tr><td colspan=\"3\"><hr /></td></tr>";*/
/////////////////

echo "<tr><td></td><td style=\"padding-left: 10px\"><input type=\"submit\" class=\"sb_but1\" name=\"submit\" value=\"Gửi yêu cầu\" class=\"sb_but\"></td></tr>";
echo "</table>";
echo "<input type=\"hidden\" name=\"submit_up\" value=\"1\">";
echo "</form>";
echo "</div>";
####################
CloseTab();
include_once("footer.php");
?>