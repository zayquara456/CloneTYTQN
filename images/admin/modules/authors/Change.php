<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$adm_pagetitle2 = _EDITADMIN;

$origAcc = isset($_GET['acc']) ? $_GET['acc'] : $_POST['acc'];
$acc = $escape_mysql_string(trim($origAcc));

$result = $db->sql_query("SELECT adname, email, pwd, pwd2, permission, mods, menus FROM ".$prefix."_admin WHERE adacc='$acc'");
if(empty($acc) || $db->sql_numrows($result) != 1) {
	header("Location: modules.php?f=$adm_modname"); exit;
}

$ds_acc = $ds_adname = $ds_email = $ds_pass = $ds_pass2 = "none";

$stopnick = _ERROR1;

list($adname, $email, $pwdold, $pwdold2, $permission, $mods, $menus) = $db->sql_fetchrow($result);
$adacc = $acc;
$adname_old = $adname;
$auth_modules = @explode("|",$mods);
$auth_menus = @explode("|",$menus);
include("page_header.php");

$err_mail = $err_pass = $password = $password2 = "";
//$permission ="";
if(isset($_POST['subup']) && $_POST['subup'] == 1) {
	$password = $_POST['password'];
	$password2 = $_POST['password2'];
	$passwordnew = $_POST['passwordnew'];
	$passwordnew2 = $_POST['passwordnew2'];
	$passwordrenew= $_POST['passwordrenew'];
	$passwordrenew2 = $_POST['passwordrenew2'];

	if($password !="" && (strlen($password) < 3 || strlen($password) > 10 || strrpos($password,' ') > 0)) {
		$err_pass ="<font color=\"red\">"._ERROR4."</font><br/>";
		$err = 1;
	}
	
	if($password2 !="" && (strlen($password2) < 3 || strlen($password2) > 10 || strrpos($password2,' ') > 0)) {
		$err_pass2 ="<font color=\"red\">"._ERROR4."</font><br/>";
		$err = 1;
	}

	if($adname_old != "Root") {
		if($permission == 1) {
			$auth_modules ="";
			$modlist = "";
		}else{
			$permission = 0;
			$modlist = @implode("|",$auth_modules);
		}
	}

	$menulist = @implode("|",$auth_menus);

	if(!$err) {
		if($password !="") {
			$password = md5($password);
		}else {
			$password = $pwdold;
		}
		if($password2 !="") {
			$password2 = md5($password2);
		}else {
			$password2 = $pwdold2;
		}
		
		if($adname_old =="Root") {
			$db->sql_query("UPDATE ".$prefix."_admin SET adacc='$adacc', pwd='$password2',pwd2='$password', email='$email' WHERE adacc='$acc'");
		}else{
			$db->sql_query("UPDATE ".$prefix."_admin SET adacc='$adacc', adname='$adname', pwd='$password2',pwd2='$password', email='$email', permission='$permission', mods='$modlist', menus='$menulist' WHERE adacc='$acc'");
		}
		updateadmlog($admin_ar[0], $adm_modname, "Thay đổi mật khẩu	", _EDIT);
		header("Location: modules.php?f=$adm_modname"); exit;
	}

}

if($adname_old == "Root") {$css =" disabled"; }else{ $css =""; }
ajaxload_content();
echo "<div id=\"pagecontent\">";
echo "<form action=\"modules.php?f=$adm_modname&do=$do&acc=$origAcc\" method=\"POST\" onSubmit=\"return checkEditAuthor(this);\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
echo "<tr><td colspan=\"2\" class=\"header\">Thay đổi mật khẩu</td></tr>";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._ATACC."</b></td>\n";
echo "<td class=\"row3\">".errorMess($ds_acc, "adacc", $stopnick)."<input type=\"text\" disabled=\"disabled\" name=\"adacc\" value=\"$adacc\" size=\"30\"></td>\n";
echo "</tr>\n";
if($adname_old != "Root") {
	echo "<tr>\n";
	echo "<td align=\"right\" class=\"row1\"><b>"._ATNAME."</b></td>\n";
	echo "<td class=\"row3\">".errorMess($ds_adname, "adname", _ERROR2)."<input type=\"text\" disabled=\"disabled\" name=\"adname\" value=\"$adname\" size=\"30\"></td>\n";
	echo "</tr>\n";
}else{
	echo "<tr>\n";
	echo "<td align=\"right\" class=\"row1\"><b>"._ATNAME."</b></td>\n";
	echo "<td class=\"row3\"><input type=\"text\" name=\"adname\" value=\"$adname_old\" disabled=\"disabled\" size=\"30\" disabled></td>\n";
	echo "</tr>\n";
}
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>Email</b></td>\n";
echo "<td class=\"row3\">".errorMess($ds_email, "email", _ERROR3)."$err_mail<input type=\"text\" disabled=\"disabled\" name=\"email\" value=\"$email\" size=\"30\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
if(!defined('iS_SADMIN'))
{
	echo "<tr><td align=\"right\" class=\"row1\"><b>"._GENERALADM.":</b></td>";
	if($permission == 1) {$seld2 =" checked";} else { $seld2 =""; }
	echo "<td class=\"row3\"><input type=\"checkbox\" name=\"permission\" value=\"1\" size=\"40\"$seld2$css></td></tr>";
}
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._PASSWORD_OLD."</b></td>\n";
echo "<td class=\"row3\">$err_pass<input type=\"text\" name=\"password\" value=\"\" size=\"30\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._PASSWORD_NEW."</b></td>\n";
echo "<td class=\"row3\">$err_passnew<input type=\"text\" name=\"passwordnew\" value=\"\" size=\"30\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._PASSWORD_RENEW."</b></td>\n";
echo "<td class=\"row3\">$err_passrenew<input type=\"text\" name=\"passwordrenew\" value=\"\" size=\"30\"></td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._PASSWORD_OLD2."</b></td>\n";
echo "<td class=\"row3\">$err_pass2<input type=\"text\" name=\"password2\" value=\"\" size=\"30\"></td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._PASSWORD_NEW2."</b></td>\n";
echo "<td class=\"row3\">$err_passnew2<input type=\"text\" name=\"passwordnew2\" value=\"\" size=\"30\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._PASSWORD_RENEW2."</b></td>\n";
echo "<td class=\"row3\">$err_passrenew2<input type=\"text\" name=\"passwordrenew2\" value=\"\" size=\"30\"></td>\n";
echo "</tr>\n";
echo "<tr><td class=\"row3\">&nbsp;</td><td class=\"row3\"><input type=\"hidden\" name=\"acc\" value=\"$acc\"><input type=\"hidden\" name=\"subup\" value=\"1\"><input type=\"submit\" class=\"button2\" name=\"submit\" value=\""._SAVECHANGES."\"> <input type=\"button\" value=\""._CANCEL."\" class=\"button2\" onclick=\"window.location='modules.php?f=$adm_modname'\"></td></tr>";
echo "</table></form></div>\n";

include_once("page_footer.php");
?>