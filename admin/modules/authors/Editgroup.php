<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$adm_pagetitle2 = "Chính sửa nhóm quản trị";
$id = isset($_GET['id']) ? $_GET['id'] : $_POST['id'];
$result = $db->sql_query("SELECT * FROM ".$prefix."_admingroup WHERE id='$id'");
if(empty($id) || $db->sql_numrows($result) != 1) {
	header("Location: modules.php?f=$adm_modname&do=group"); exit;
}
list($idgroup, $titlegroup, $permissiongroup, $activegroup) = $db->sql_fetchrow($result);
$auth_menus = @explode("|",$permissiongroup);

$adacc = $adname = $err_mail = $email = $permission = $err_pass = $password= $password2 = $acc = $css= $error= "";
$ds_acc = $ds_adname = $ds_email = $ds_pass= $ds_pass2 = "none";


include("page_header.php");

if(isset($_POST['subup']) && $_POST['subup'] == 1) {
	$title = trim(stripslashes(resString($_POST['title'])));
	$active = intval($_POST['active']);
	$auth_menus = $_POST['auth_menus'];
	if(empty($title)) {
		$title = "";
		$error = "<font color=\"red\">Mời bạn nhập tên nhóm</font><br/>";
		$err = 1;
	}
	$menulist = @implode("|",$auth_menus);
	echo $menulist;
	if(!$err) {
		//$db->sql_query("UPDATE ".$prefix."_admin SET adacc='$adacc', pwd='$password2',pwd2='$password', email='$email' WHERE adacc='$acc'");
		//die("UPDATE ".$prefix."_admingroup title='$title',permission='$menulist',active=$active WHERE id=$idgroup");
		$db->sql_query("UPDATE ".$prefix."_admingroup SET title='$title',permission='$menulist',active=$active WHERE id=$idgroup");
		updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, "Chỉnh sửa nhóm quản trị");
		header("Location: modules.php?f=".$adm_modname."&do=group");
	}
}
ajaxload_content();
echo "<div id=\"pagecontent\">";
echo "<form action=\"modules.php?f=$adm_modname&do=$do&id=$idgroup\" method=\"POST\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" class=\"tableborder\">\n";
echo "<tr><td colspan=\"2\" class=\"header\">Chính sửa nhóm quản trị mới</td></tr>";
echo "<tr>\n";
echo "<td width=\"150\" align=\"right\" class=\"row1\"><b>Tên nhóm</b></td>\n";
echo "<td class=\"row3\">".$error."<input type=\"text\" name=\"title\" value=\"$titlegroup\" size=\"30\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
if($activegroup==1)
{
	echo "<td align=\"right\" class=\"row1\"><b>Trạng thái</b></td>\n";
	echo "<td class=\"row2\"><input type=\"radio\" name=\"active\" value=\"1\" checked>"._YES." &nbsp;&nbsp;";
	echo "<input type=\"radio\" name=\"active\" value=\"0\">"._NO."</td>\n";
}
else
{
	echo "<td align=\"right\" class=\"row1\"><b>Trạng thái</b></td>\n";
	echo "<td class=\"row2\"><input type=\"radio\" name=\"active\" value=\"1\" >"._YES." &nbsp;&nbsp;";
	echo "<input type=\"radio\" name=\"active\" value=\"0\" checked>"._NO."</td>\n";
}
echo "</tr>\n";

echo "<tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\" valign=\"top\"><b>"._MENUPERMISSION."</b></td>\n";
echo "<td class=\"row3\">";
echo "<table border=\"0\" width=\"100%\">";

$where="menu_type='admin_menu' AND";
$result_cat = $db->sql_query("SELECT mid, title FROM ".$prefix."_adminmenus WHERE $where parentid='0' ORDER BY weight");
if($db->sql_numrows($result_cat) > 0) {
	$listcat ="";
	while(list($m_id, $titlecat) = $db->sql_fetchrow($result_cat)) {
			//if($m_id == $parentid) {$seld =" selected"; }else{ $seld ="";}
			//$listcat .= "<option value=\"$m_id\"$seld>--$titlecat</option>";
			$listcat .= "<tr><td><strong>".$titlecat."</strong></td>";
			$listcat .= "".subcat($m_id,"",$auth_menus,"",$css)."</tr><tr><td colspan=\"5\"><div style=\"border-top:1px solid #cccccc;\"></div></td></tr>";
		}
		echo $listcat;
}
echo "</table>";
echo "</td>\n";
echo "</tr>\n";
echo "<tr><td colspan=\"2\" align=\"center\" class=\"row4\"><input type=\"hidden\" name=\"acc\" value=\"$acc\"><input type=\"hidden\" name=\"subup\" value=\"1\"><input type=\"submit\" class=\"button2\" name=\"submit\" value=\"Cập nhật\"> <input  class=\"button2\" type=\"button\" value=\""._CANCEL."\" onclick=\"window.location='modules.php?f=$adm_modname&do=group'\"></td></tr>";
echo "</table></form>\n";
echo "</div>";
include_once("page_footer.php");
?>