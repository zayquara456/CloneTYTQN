<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$adm_pagetitle2 = _ADDAUTHOR;

$adacc = $adname = $err_mail = $email = $permission = $err_pass = $password= $password2 = $passwordre= $passwordre2 = $acc = "";
$ds_acc = $ds_adname = $ds_email = $ds_pass= $ds_pass2 = $ds_passre= $ds_passre2 = "none";

$stopnick = _ERROR1;

include("page_header.php");

if(isset($_POST['subup']) && $_POST['subup'] == 1) {
	$adacc = trim(stripslashes(resString($_POST['adacc'])));
	$adname = trim(stripslashes(resString($_POST['adname'])));
	$email = trim(stripslashes(resString($_POST['email'])));
	$permission = $_POST['permission'];
	if(!defined('iS_SADMIN'))
	{
		//$auth_modules = $_POST['auth_modules'];
		//$auth_menus = $_POST['auth_menus'];
		//$permission = intval($_POST['permission']);
	}
	$password = $_POST['password'];
	$passwordre = $_POST['passwordre'];
	$stopick ="";
	$stopnick = AccCheck($adacc,$acc);
	if($stopnick) {
		$ds_acc = "";
		$err = 1;
	}
	

	if(empty($adname) || (!empty($adname) && $adname == "Root")) {
		$ds_adname = "";
		$err = 1;
	}

	if(!is_email($email)) {
		$ds_email = "";
		$err = 1;
	}

	if ($db->sql_numrows($db->sql_query("SELECT email FROM ".$prefix."_admin WHERE email='$email'")) > 0) {
		$err_mail = "<font color=\"red\">"._ERROR3_1."</font><br/>";
		$err = 1;
	}

	if($password =="") {
		$ds_pass ="";
		$err = 1;
	}
	if($password!=$passwordre) {
		$ds_passre ="";
		$err = 1;
	}
	if($password !="" && (strlen($password) < 3 || strlen($password) > 10 || strrpos($password,' ') > 0)) {
		$err_pass ="<font color=\"red\">"._ERROR4."</font><br/>";
		$err = 1;
	}
	/*if($permission == 1) {
		$auth_modules ="";
		$modlist = "";
	}else{
		$permission = 0;
		$modlist = @implode("|",$auth_modules);
	}*/
	
	$menulist = @implode("|",$auth_menus);

	if(!$err) {
		$password = md5($password);
		$db->sql_query("INSERT INTO ".$prefix."_admin (adacc, adname, email, pwd, permission, mods, menus) VALUES ('$adacc', '$adname', '$email', '$password', '$permission', '$modlist', '$menulist')");
		updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _ADD);
		header("Location: modules.php?f=".$adm_modname);
	}

}
ajaxload_content();
echo "<div id=\"pagecontent\">";
echo "<form action=\"modules.php?f=$adm_modname&do=$do\" method=\"POST\" onSubmit=\"return checkAddAuthor(this);\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" class=\"tableborder\">\n";
echo "<tr><td colspan=\"2\" class=\"header\">"._ADDAUTHOR."</td></tr>";
echo "<tr>\n";
echo "<td width=\"150\" align=\"right\" class=\"row1\"><b>"._ATACC."</b></td>\n";
echo "<td class=\"row3\">".errorMess($ds_acc, "adacc", $stopnick)."<input type=\"text\" name=\"adacc\" value=\"$adacc\" size=\"30\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._ATNAME."</b></td>\n";
echo "<td class=\"row3\">".errorMess($ds_adname, "adname", _ERROR2)."<input type=\"text\" name=\"adname\" value=\"$adname\" size=\"30\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>Email</b></td>\n";
echo "<td class=\"row3\">".errorMess($ds_email, "email", _ERROR3)."$err_mail<input type=\"text\" name=\"email\" value=\"$email\" size=\"30\"></td>\n";
echo "</tr>\n";

$result_cat = $db->sql_query("SELECT id, title FROM ".$prefix."_admingroup");
if($db->sql_numrows($result_cat) > 0) {
echo "<tr>\n";
echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>Nhóm quản trị</b></td>\n";
echo "<td class=\"row2\"><select name=\"permission\">";
//echo "<option name=\"mid\" value=\"0\">"._INMENU0."</option>";
	$listcat ="";
	while(list($m_id, $titlecat) = $db->sql_fetchrow($result_cat)) {
			if($m_id == $parentid) {$seld =" selected"; }else{ $seld ="";}
			$listcat .= "<option value=\"$m_id\"$seld>$titlecat</option>";
		}
		echo $listcat;
echo "</select></td>\n";
echo "</tr>\n";
}
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._PASSWORD."</b></td>\n";
echo "<td class=\"row3\">".errorMess($ds_pass, "password", _ERROR4)."$err_pass<input type=\"text\" name=\"password\" value=\"\" size=\"30\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>Nhập lại mật khẩu</b></td>\n";
echo "<td class=\"row3\">".errorMess($ds_passre, "passwordre",  "Mật khẩu không giống nhau")."$err_pass<input type=\"text\" name=\"passwordre\" value=\"\" size=\"30\"></td>\n";
echo "</tr>\n";
echo "<tr><td colspan=\"2\" align=\"center\" class=\"row4\"><input type=\"hidden\" name=\"acc\" value=\"$acc\"><input type=\"hidden\" name=\"subup\" value=\"1\"><input type=\"submit\" class=\"button2\" name=\"submit\" value=\""._ADD."\"> <input class=\"button2\" type=\"button\" value=\""._CANCEL."\" onclick=\"window.location='modules.php?f=$adm_modname'\"></td></tr>";
echo "</table></form></div>\n";

include_once("page_footer.php");
?>