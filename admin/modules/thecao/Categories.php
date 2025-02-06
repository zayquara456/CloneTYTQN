<?php

if(!defined('CMS_ADMIN')) die("Illegal File Access");

include("page_header.php");

$active = 1;
$title = $err_title ="";
$catid = isset($_GET['catid']) ? intval($_GET['catid']) : 0;


$result = $db->sql_query("SELECT title, active, parentid FROM ".$prefix."_thecao_cat WHERE catid='$catid' AND alanguage='$currentlang'");
if(empty($catid) || $db->sql_numrows($result) != 1) {
	//header("Location: ".$adm_modname.".php");
	//die();
}
else{
	list($title, $active, $parentid) = $db->sql_fetchrow($result);	
}
// update card
if( isset($_POST['subup']) && $_POST['subup'] == 1) {
	$title = trim(stripslashes(resString($_POST['title'])));
	$active = intval($_POST['active']);
	$parentid = intval($_POST['parentid']);
	$catid = intval($_POST['catid']);

	if($title =="") {
		$err_title = "<font color=\"red\">"._ERROR1."</font><br/>";
		$err = 1;
	}

	if(!$err) {
		$db->sql_query("UPDATE ".$prefix."_thecao_cat SET title='$title', active='$active', parentid='$parentid' WHERE catid='$catid'");
		fixweight_cat();
		fixsubcat();
		updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _EDITCAT);
		header("Location: modules.php?f=".$adm_modname."&do=categories");
	}
}
// insert card
if( isset($_POST['subin'])&& $_POST['subin'] == 1) {
	$title = trim(stripslashes(resString($_POST['title'])));
	$active = intval($_POST['active']);
	$parentid = intval($_POST['parentid']);

	if($title =="") {
		$err_title = "<font color=\"red\">"._ERROR1."</font><br/>";
		$err = 1;
	}

	if(!$err) {
		$weight = WeightMax("thecao_cat");
		$db->sql_query("INSERT INTO ".$prefix."_thecao_cat (catid, parentid, title, alanguage, active, weight) VALUES (NULL, '$parentid', '$title', '$currentlang', '$active', '$weight')");
		fixweight_cat();
		fixsubcat();
		updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _CREATECAT);
		header("Location: modules.php?f=".$adm_modname."&do=$do");
	}
}

echo "<div id=\"".$adm_modname."_main\">\n";
ajaxload_content();

echo "<script language=\"javascript\">\n";
echo "	function check(f) {\n";
echo "		if(f.title.value =='') {\n";
echo "			alert('"._ERROR1."');\n";
echo "			f.title.focus();\n";
echo "			return false;\n";
echo "		}\n";
echo "		f.submit.disabled = true;\n";
echo "		return true;	\n";
echo "	}	\n";
echo "</script>	\n";
echo "<div id=\"pagecontent\">";
echo "<form action=\"modules.php?f=$adm_modname&do=$do\" method=\"POST\" onsubmit=\"return check(this);\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
echo "<tr><td colspan=\"2\" class=\"header\">Tạo loại thẻ</td></tr>";
echo "<tr>\n";
echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>Tên thẻ</b></td>\n";
echo "<td class=\"row2\">$err_title<input type=\"text\" name=\"title\" value=\"$title\" size=\"50\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._ACTIVE."</b></td>\n";
if($active == 1) {
	echo "<td  class=\"row2\"><input type=\"radio\" name=\"active\" value=\"1\" checked>"._YES." &nbsp;&nbsp;"
	."<input type=\"radio\" name=\"active\" value=\"0\">"._NO."</td>\n";
} else {
	echo "<td  class=\"row2\"><input type=\"radio\" name=\"active\" value=\"1\">"._YES." &nbsp;&nbsp;"
	."<input type=\"radio\" name=\"active\" value=\"0\" checked>"._NO."</td>\n";
}
echo "</tr>\n";
if(empty($catid)){
	echo "<input type=\"hidden\" name=\"subin\" value=\"1\">";
	echo "<tr><td colspan=\"2\" align=\"center\" class=\"row1\"><input type=\"submit\" class=\"button2\" name=\"submit\" value=\""._ADD."\"></td></tr>";
}
else{
	echo "<input type=\"hidden\" name=\"catid\" value=\"$catid\">";
	echo "<input type=\"hidden\" name=\"subup\" value=\"1\">";
	echo "<tr><td colspan=\"2\" align=\"center\" class=\"row1\"><input type=\"submit\" class=\"button2\" name=\"submit\" value=\"Cập nhật\"></td></tr>";
}
echo "</table></form></div>";

$resultcat = $db->sql_query("SELECT catid, parentid, title, active, weight, counts  FROM ".$prefix."_thecao_cat WHERE parentid='0' AND alanguage='$currentlang' ORDER BY weight,catid ASC");
if($db->sql_numrows($resultcat) > 0) {
	echo "<script language=\"javascript\" type=\"text/javascript\">\n";
	echo "function check_uncheck(){\n";
	echo "	var f= document.frm;\n";
	echo "	if(f.checkall.checked){\n";
	echo "		CheckAllCheckbox(f,'catid[]');\n";
	echo "	}else{\n";
	echo "		UnCheckAllCheckbox(f,'catid[]');\n";
	echo "	}			\n";
	echo "}\n";
	echo "	function checkQuick(f) {\n";
	echo "		if(f.fc.value =='') {\n";
	echo "			f.fc.focus();\n";
	echo "			return false;\n";
	echo "		}\n";
	echo "		return true;		\n";
	echo "	}	\n";
	echo "</script>\n";
	echo "<br/>";
	echo "<div id=\"pagecontent\">";
	echo "<form name=\"frm\" action=\"modules.php?f={$adm_modname}\" method=\"POST\" onsubmit=\"return checkQuick(this);\">";
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
	echo "<tr><td colspan=\"10\" class=\"header\">Các loại thẻ</td></tr>";
	$listcat ="";
	$listcat .= "	<tr>\n";
	$listcat .= "<td width=\"1%\" align=\"center\" class=\"row1sd\"><input type=\"checkbox\" name=\"checkall\" onclick=\"javascript:check_uncheck();\" title=\""._CHECKALL."\"></td>\n";
	$listcat .= "		<td class=\"row1sd\">"._TITLE."</td>\n";
	$listcat .= "<td align=\"center\" width=\"50\" class=\"row1sd\">"._WEIGHT."</td>\n";
	$listcat .= "<td align=\"center\" width=\"60\" class=\"row1sd\">Số lượng</td>\n";
	$listcat .= "		<td align=\"center\" width=\"50\" class=\"row1sd\"><b>"._SHOW."</b></td>\n";
	$listcat .= "<td align=\"center\" width=\"30\" class=\"row1sd\">"._EDIT."</td>\n";
	$listcat .= "<td align=\"center\" width=\"30\" class=\"row1sd\">"._DELETE."</td>\n";
	$listcat .= "	</tr>\n";
	while(list($catid, $parentid, $title, $active, $weight, $counts) = $db->sql_fetchrow($resultcat)) {
			switch($active) {
				case 1: $active = "<img border=\"0\" src=\"images/view.png\">"; break;
				case 0: $active = "<img border=\"0\" src=\"images/viewo.png\">"; break;
			}

		$listcat .= "	<tr>\n";
		$listcat .= "<td align=\"center\" class=\"row1\"><input type=\"checkbox\" name=\"catid[]\" value=\"$catid\"></td>\n";
			$listcat .= "<td class=\"row1\"><b><a href=\"modules.php?f=$adm_modname&do=menhgia&catid=$catid\">$title</a></b></td>\n";
		

		$listcat .= "<td align=\"center\" class=\"row1\"><input type=\"text\" name=\"poz[$catid]\" value=\"$weight\" maxlength=\"2\" style=\"text-align: center; width: 30px\"></td>\n";
		$listcat .= "<td align=\"center\" class=\"row1\">$counts</td>\n";
		$listcat .= "		<td align=\"center\" class=\"row1\">$active</td>\n";
		$listcat .= "<td align=\"center\" class=\"row1\"><a href=\"modules.php?f=$adm_modname&do=categories&catid=$catid\" info=\""._EDIT."\"><img border=\"0\" src=\"../images/edit.gif\"></a></td>\n";
		$listcat .= "<td align=\"center\" class=\"row1\"><a href=\"modules.php?f=$adm_modname&do=delete_cat&catid=$catid\" info=\""._DELETE."\" onclick=\"return confirm('"._DELETEASK."');\"><img border=\"0\" src=\"images/delete.png\"></td>\n";
		//$listcat .= childcat($catid);
	}
	echo $listcat;
	echo "<input type=\"hidden\" name=\"do\" value=\"quick_do_cat\">";
	echo "<tr><td colspan=\"8\"><select name=\"fc\">";
	//echo "<option value=\"\">&raquo; "._QUICKDO."</option>";
	//echo "<option value=\"2\">&raquo; "._QUICKDO_2."</option>";
	//echo "<option value=\"3\">&raquo; "._QUICKDO_3."</option>";
	echo "<option value=\"4\">&raquo; "._QUICKDO_4."</option>";
	echo "</select> <input type=\"submit\" value=\""._DOACTION."\"></td></tr>";
	echo "";
	echo "</table></form><br/>";
}

echo "</div></div>\n";

include_once("page_footer.php");

?>