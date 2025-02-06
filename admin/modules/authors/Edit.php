<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");
global $super_admin;
$adm_pagetitle2 = _EDITADMIN;

$origAcc = isset($_GET['acc']) ? $_GET['acc'] : $_POST['acc'];
$acc = $escape_mysql_string(trim($origAcc));

$result = $db->sql_query("SELECT adname, email, pwd, permission, mods, menus FROM ".$prefix."_admin WHERE adacc='$acc'");

if(empty($acc) || $db->sql_numrows($result) != 1) {
	header("Location: modules.php?f=$adm_modname"); exit;
}

$ds_acc = $ds_adname = $ds_email = $ds_pass  = "none";
$err ="";
$stopnick = _ERROR1;

list($adname, $email, $pwdold, $permissionold, $mods, $menus) = $db->sql_fetchrow($result);
$resultspadmin = $db->sql_query("SELECT id, permission FROM ".$prefix."_admin WHERE adacc='$admin_ar[0]'");

if($db->sql_numrows($resultspadmin) == 1) {
	list($idsuperadmin,$permissionspadmin) = $db->sql_fetchrow($resultspadmin);
	if($idsuperadmin!=$super_admin)
		die("Ban khong co quyen truy cap!");
}

//if($permissionold!=2)
//{
//	die("Illegal File Access");
//}
$adacc = $acc;
$adname_old = $adname;
//$auth_modules = @explode("|",$mods);
//$auth_menus = @explode("|",$menus);
include("page_header.php");

$err_mail = $err_pass  = $password = "";
$permission ="";
if(isset($_POST['subup']) && $_POST['subup'] == 1) {
	$adacc = $escape_mysql_string(trim($_POST['adacc']));
	$adname = $escape_mysql_string(trim($_POST['adname']));
	$email = $escape_mysql_string(trim($_POST['email']));
	if(!defined('iS_SADMIN'))
	{
		//$auth_modules = $_POST['auth_modules'];
		//$auth_menus = $_POST['auth_menus'];
		$permission = intval($_POST['permission']);
		
	}
	$password = $_POST['password'];
	
	$stopnick = AccCheck($adacc, $acc);
	if($stopnick) {
		$err_acc = "<font color=\"red\">$stopnick</font><br/>";
		$err = 1;
	}


	if ($adname_old != "Root") {
		if(empty($adname) || (!empty($adname) && $adname == "Root")) {
			$adname ="";
			$err_title = "<font color=\"red\">"._ERROR2."</font><br/>";
			$err = 1;
		}
	}

	if(!is_email($email)) {
		$ds_email = "";
		$err = 1;
	}

	if ($db->sql_numrows($db->sql_query("SELECT email FROM ".$prefix."_admin WHERE email='$email' AND adacc!='$acc'")) > 0) {
		$err_mail = "<font color=\"red\">"._ERROR3_1."</font><br/>";
		$err = 1;
	}

	if($password !="" && (strlen($password) < 3 || strlen($password) > 10 || strrpos($password,' ') > 0)) {
		$err_pass ="<font color=\"red\">"._ERROR4."</font><br/>";
		$err = 1;
	}
	
	/*if($adname_old != "Root") {
		if($permission == 1) {
			$auth_modules ="";
			$modlist = "";
		}else{
			$permission = 0;
			$modlist = @implode("|",$auth_modules);
		}
	}*/

	//$menulist = @implode("|",$auth_menus);

	if(!$err) {
		if($password !="") {
			$password = md5($password);
		}else {
			$password = $pwdold;
		}
		
		if($adname_old =="Root") {
			$db->sql_query("UPDATE ".$prefix."_admin SET adacc='$adacc', pwd='$password', email='$email' WHERE adacc='$acc'");
		}else{
			$db->sql_query("UPDATE ".$prefix."_admin SET adacc='$adacc', adname='$adname', pwd='$password', email='$email', permission='$permission' WHERE adacc='$acc'");
		}
		updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _EDIT);
		header("Location: modules.php?f=$adm_modname"); exit;
	}

}

if($adname_old == "Root") {$css ="disabled"; }else{ $css =""; }

ajaxload_content();
echo "<div id=\"pagecontent\">";
echo "<form action=\"modules.php?f=$adm_modname&do=$do&acc=$origAcc\" method=\"POST\" onSubmit=\"return checkEditAuthor(this);\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
echo "<tr><td colspan=\"2\" class=\"header\">"._EDITADMIN."</td></tr>";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._ATACC."</b></td>\n";
echo "<td class=\"row3\">".errorMess($ds_acc, "adacc", $stopnick)."<input type=\"text\" name=\"adacc\" value=\"$adacc\" size=\"30\"></td>\n";
echo "</tr>\n";
if($adname_old != "Root") {
	echo "<tr>\n";
	echo "<td align=\"right\" class=\"row1\"><b>"._ATNAME."</b></td>\n";
	echo "<td class=\"row3\">".errorMess($ds_adname, "adname", _ERROR2)."<input type=\"text\" name=\"adname\" value=\"$adname\" size=\"30\"></td>\n";
	echo "</tr>\n";
}else{
	echo "<tr>\n";
	echo "<td align=\"right\" class=\"row1\"><b>"._ATNAME."</b></td>\n";
	echo "<td class=\"row3\"><input type=\"text\" name=\"adname\" value=\"$adname_old\" size=\"30\" disabled></td>\n";
	echo "</tr>\n";
}
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
			if($m_id == $permissionold) {$seld =" selected"; }else{ $seld ="";}
			$listcat .= "<option value=\"$m_id\"$seld>$titlecat</option>";
		}
		echo $listcat;
echo "</select></td>\n";
echo "</tr>\n";
}
/*if(!defined('iS_SADMIN'))
{
	echo "<tr><td align=\"right\" class=\"row1\"><b>"._GENERALADM.":</b></td>";
	if($permission == 1) {$seld2 =" checked";} else { $seld2 =""; }
	echo "<td class=\"row3\"><input type=\"checkbox\" name=\"permission\" value=\"1\" size=\"40\"$seld2$css></td></tr>";
}*/

echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._PASSWORD."</b></td>\n";
echo "<td class=\"row3\">$err_pass<input type=\"text\" name=\"password\" value=\"\" size=\"30\"></td>\n";
echo "</tr>\n";
echo "<tr><td class=\"row4\" colspan=\"2\" align=\"center\"><input type=\"hidden\" name=\"acc\" value=\"$acc\"><input type=\"hidden\" name=\"subup\" value=\"1\"><input type=\"submit\" name=\"submit\" class=\"button2\" value=\""._SAVECHANGES."\"> <input type=\"button\" value=\""._CANCEL."\" class=\"button2\" onclick=\"window.location='modules.php?f=$adm_modname'\"></td></tr>";
echo "</table></form>\n";
echo "</div>";
include_once("page_footer.php");
?>