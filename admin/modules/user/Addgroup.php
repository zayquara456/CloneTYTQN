<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$adm_pagetitle2 = "Thêm nhóm thành viên";

$adacc = $adname = $err_mail = $email = $permission = $err_pass = $password= $password2 = $acc = $css= $error= $error2=$error3="";
$ds_acc = $ds_adname = $ds_email = $ds_pass= $ds_pass2 = "none";

//$stopnick = _ERROR1;

include("page_header.php");

if(isset($_POST['subup']) && $_POST['subup'] == 1) {
	$title = trim(stripslashes(resString($_POST['title'])));
	$sale = intval(isset($_POST['sale'])? $_POST['sale'] : 0);
	$sale_card = intval(isset($_POST['sale_card'])? $_POST['sale_card'] : 0);
	$active = intval($_POST['active']);
	if(!defined('iS_SADMIN'))
	{
		$auth_menus = $_POST['auth_menus'];
	}
	if ($db->sql_numrows($db->sql_query("SELECT title FROM ".$prefix."_usergroup WHERE title='$title'")) > 0) {
		$error = "<font color=\"red\">Tên nhóm đã được sử dụng</font><br/>";
		$err = 1;
	}
	if(empty($title)) {
		$title = "";
		$error = "<font color=\"red\">Mời bạn nhập tên nhóm</font><br/>";
		$err = 1;
	}
	
	$menulist = "";//@implode("|",$auth_menus);
	if(!$err) {
		$db->sql_query("INSERT INTO ".$prefix."_usergroup (title, sale, sale_card, permission, active) VALUES ('$title','$sale','$sale_card','$menulist',$active)");
		updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, "Thêm nhóm thành viên");
		header("Location: modules.php?f=".$adm_modname."&do=group");
	}
}
ajaxload_content();
echo "<div id=\"pagecontent\">";
echo "<form action=\"modules.php?f=$adm_modname&do=$do\" method=\"POST\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" class=\"tableborder\">\n";
echo "<tr><td colspan=\"2\" class=\"header\">Thêm nhóm thành viên</td></tr>";
echo "<tr>\n";
echo "<td width=\"200\" align=\"right\" class=\"row1\"><b>Tên nhóm</b></td>\n";
echo "<td class=\"row3\">".$error."<input type=\"text\" name=\"title\" value=\"\" size=\"30\"></td>\n";
echo "</tr>\n";
//echo "<tr>\n";
//echo "<td width=\"200\" align=\"right\" class=\"row1\"><b>Phần trăm nạp thẻ cảo (%)</b></td>\n";
//echo "<td class=\"row3\">".$error2."<input type=\"text\" name=\"sale\" value=\"\" size=\"30\"></td>\n";
//echo "</tr>\n";
//echo "<tr>\n";
//echo "<td width=\"200\" align=\"right\" class=\"row1\"><b>Phần trăm mua thẻ carot (%)</b></td>\n";
//echo "<td class=\"row3\">".$error3."<input type=\"text\" name=\"sale_card\" value=\"\" size=\"30\"></td>\n";
//echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>Trạng thái</b></td>\n";
echo "<td class=\"row2\"><input type=\"radio\" name=\"active\" value=\"1\" checked>"._YES." &nbsp;&nbsp;";
echo "<input type=\"radio\" name=\"active\" value=\"0\">"._NO."</td>\n";
echo "</tr>\n";

echo "<tr>\n";
/*echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\" valign=\"top\"><b>"._MENUPERMISSION."</b></td>\n";
echo "<td class=\"row3\">";
echo "<table border=\"0\" width=\"100%\">";

$result_cat = $db->sql_query("SELECT catid, title FROM ".$prefix."_noibo_cat WHERE parent='0' ORDER BY weight");
if($db->sql_numrows($result_cat) > 0) {
	$listcat ="";
	while(list($m_id, $titlecat) = $db->sql_fetchrow($result_cat)) {
			//if($m_id == $parentid) {$seld =" selected"; }else{ $seld ="";}
			//$listcat .= "<option value=\"$m_id\"$seld>--$titlecat</option>";
			$listcat .= "<tr><td><strong>".$titlecat."</strong></td>";
			$listcat .= "".subnoibocat($m_id,"","","",$css)."</tr><tr><td colspan=\"5\"><div style=\"border-top:1px solid #cccccc;\"></div></td></tr>";
		}
		echo $listcat;
}
echo "</table>";
echo "</td>\n";
echo "</tr>\n";*/
echo "<tr><td></td><td align=\"left\" class=\"row4\"><input type=\"hidden\" name=\"acc\" value=\"$acc\"><input type=\"hidden\" name=\"subup\" value=\"1\"><input class=\"button2\" type=\"submit\" name=\"submit\" value=\""._ADD."\"> <input class=\"button2\" type=\"button\" value=\""._CANCEL."\" onclick=\"window.location='modules.php?f=$adm_modname'\"></td></tr>";
echo "</table></form></div>\n";

include_once("page_footer.php");
?>