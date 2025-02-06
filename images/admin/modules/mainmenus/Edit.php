<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$mid22 = intval(isset($_GET['mid']) ? $_GET['mid'] : $_POST['mid']);
$menu_type =isset($_GET['menu_type'])?$_GET['menu_type']:"";
if($menu_type!="")
	$where="menu_type='$menu_type' AND";
else
	header("Location: modules.php?f=".$adm_modname."&menu_type=main_menu");
	
$result = $db->sql_query("SELECT title, url, parentid, target FROM ".$prefix."_mainmenus WHERE $where mid='$mid22'");
if(empty($mid22) || $db->sql_numrows($result) != 1) {
	header("Location: $adm_modname.php");
	exit;
}

list($title, $url, $parentid, $newwindow) = $db->sql_fetchrow($result);

$err_title = $err_url = "";
if(isset($_POST['subup']) && $_POST['subup'] == 1) {
	$title = trim(stripslashes(resString($_POST['title'])));
	$url = nospatags($_POST['url']);
	$newwindow = isset($_POST['newwindow']) ? intval($_POST['newwindow']) : 0;
	//$menu_type="main_menu";
	$parentid= intval($_POST['parentid']);
	
	$err = 0;
	if($title =="") {
		$err_title = "<font color=\"red\">"._ERROR1."</font><br/>";
		$err = 1;
	}

	if($url =="") {
		$err_url = "<font color=\"red\">"._ERROR2."</font><br/>";
		$err = 1;
	}

	if(!$err) {
		$db->sql_query("UPDATE ".$prefix."_mainmenus SET parentid='$parentid', title='$title', url='$url', target='$newwindow', menu_type='$menu_type' WHERE mid='$mid22'");
		fixweight_mn();
		Header("Location: modules.php?f=".$adm_modname."&menu_type=$menu_type");
		exit();
	}
}

include("page_header.php");

echo "<script language=\"javascript\">\n";
echo "	function check(f) {\n";
echo "		if(f.title.value =='') {\n";
echo "			alert('"._ERROR1."');\n";
echo "			f.title.focus();\n";
echo "			return false;\n";
echo "		}\n";
echo "		if(f.url.value =='') {\n";
echo "			alert('"._ERROR2."');\n";
echo "			f.url.focus();\n";
echo "			return false;\n";
echo "		}\n";
echo "		f.submit.disabled = true;\n";
echo "		return true;	\n";
echo "	}	\n";
echo "</script>	\n";
echo "
<div id=\"pagecontent\">";
echo "<form action=\"modules.php?f=$adm_modname&do=$do&menu_type=$menu_type&mid=$mid22\" method=\"POST\" onsubmit=\"return check(this);\" enctype=\"multipart/form-data\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
echo "<tr><td colspan=\"2\" class=\"header\">"._EDITMENU."</td></tr>";
echo "<tr>\n";
echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>"._TITLE."</b></td>\n";
echo "<td class=\"row2\">$err_title<input type=\"text\" name=\"title\" value=\"$title\" size=\"40\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>"._URL."</b></td>\n";
echo "<td class=\"row2\">$err_url<input type=\"text\" name=\"url\" value=\"$url\" size=\"50\"></td>\n";
echo "</tr>\n";
$result_cat = $db->sql_query("SELECT mid, title FROM ".$prefix."_mainmenus WHERE $where mid!='$mid22' AND parentid='0' AND alanguage='$currentlang' ORDER BY weight");
if($db->sql_numrows($result_cat) > 0) {
echo "<tr>\n";
echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>"._INMENU."</b></td>\n";
echo "<td class=\"row2\"><select name=\"parentid\">";
echo "<option name=\"mid\" value=\"0\">"._INMENU0."</option>";
	$listcat ="";
	while(list($m_id, $titlecat) = $db->sql_fetchrow($result_cat)) {
			if($m_id == $parentid) {$seld =" selected"; }else{ $seld ="";}
			$listcat .= "<option value=\"$m_id\" $seld>-- $titlecat</option>";
			$listcat .= subcat($m_id,"",$parentid, $mid);
		}	
		echo $listcat;
echo "</select></td>\n";		
echo "</tr>\n";
}
if($newwindow == 1) { $seld =" checked"; } else { $seld =""; }
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._NEWWINDOW."</b></td>\n";
echo "<td class=\"row2\"><input type=\"checkbox\" name=\"newwindow\" value=\"1\"".$seld."></td>\n";
echo "</tr>\n";
echo "<input type=\"hidden\" name=\"subup\" value=\"1\">";
echo "<tr><td colspan=\"2\" align=\"center\" class=\"row1\"><input  class=\"button2\"  type=\"submit\" name=\"submit\" value=\""._SAVECHANGES."\"></td></tr>";
echo "</table></form></div>";

include_once("page_footer.php");

?>