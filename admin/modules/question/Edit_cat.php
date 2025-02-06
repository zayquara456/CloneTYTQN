<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$catid = intval($_GET['id']);

$err_title = $err = "";
if (isset($_POST['subup']) && $_POST['subup'] == 1) {
	$title = $escape_mysql_string(trim($_POST['title']));
	$active = intval($_POST['active']);
	$onhome = 0;
	$homelinks = 0;
	$parent = intval($_POST['parent']);

	if (empty($title)) {
		$err_title = "<font color=\"red\">"._ERROR1."</font><br>";
		$err = 1;
	}
	else
	{
		$permalink=url_optimization(trim($title));
	}
	if (!$err) {
		$guid="index.php?f=question&do=categories&id=$catid";
		$db->sql_query("UPDATE ".$prefix."_question_cat SET title='$title',permalink='$permalink',guid='$guid', active='$active', onhome='$onhome', homelinks='$homelinks', parent=$parent WHERE catid='$catid'");
		fixweight_cat();
		updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _EDIT_CAMNANG_CAT);
		header("Location: modules.php?f=".$adm_modname."&do=categories");
	}
}

$result = $db->sql_query("SELECT title, active, onhome, homelinks, parent FROM ".$prefix."_question_cat WHERE catid=$catid AND alanguage='$currentlang'");
if($db->sql_numrows($result) != 1) {
	header("Location: modules.php?f=".$adm_modname."&do=categories");;
	die();
}
list($title, $active, $onhome, $homelinks, $parent) = $db->sql_fetchrow($result);

include_once("page_header.php");

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

echo "<form method=\"POST\" action=\"modules.php?f=question&do=edit_cat&id=$catid\" onsubmit=\"return check(this);\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
echo "<tr><td colspan=\"2\" class=\"header\">"._MODTITLE." &raquo; "._EDITCAT."</td></tr>";
echo "<tr>\n";
echo "<td width=\"30%\" align=\"right\" class=\"row1\"><b>"._TITLE."</b></td>\n";
echo "<td class=\"row2\">$err_title<input type=\"text\" name=\"title\" value=\"$title\" size=\"50\"></td>\n";
echo "</tr>\n";

//hien thi tat ca cac cap danh muc tin tuc
	$resultcat = $db->sql_query("SELECT catid, title FROM ".$prefix."_question_cat WHERE parent='0' AND catid!='$catid' AND alanguage='$currentlang' ORDER BY weight");
	if($db->sql_numrows($resultcat) > 0) {
		echo "<tr bgcolor=\"$scolor1\">\n";
		echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>"._IS_SUBCAT_OF."</b></td>\n";
		echo "<td class=\"row2\"><select name=\"parent\">";
		echo "<option name=\"catid\" value=\"0\">"._ROOT_CAT."</option>";
		$listcat ="";
		while(list($cat_id, $titlecat) = $db->sql_fetchrow($resultcat)) {
			if($cat_id == $parent) {$seld =" selected"; }else{ $seld ="";}
			$listcat .= "<option value=\"$cat_id\"$seld>--$titlecat</option>";
			$listcat .= subcat($cat_id,"-",$catid, $catid);
		}
		echo $listcat;
		echo "</select></td>\n";
		echo "</tr>\n";
	}

echo "<tr bgcolor=\"#F7F7F7\">\n";
echo "<td align=\"right\" class=\"row1\"><b>"._SHOW."</b></td>\n";
if ($active == 1) {
	echo "<td class=\"row2\"><input type=\"radio\" name=\"active\" value=\"1\" checked>"._YES." &nbsp;&nbsp;"
	."<input type=\"radio\" name=\"active\" value=\"0\">"._NO."</td>\n";
	echo "</tr>\n";
} else {
	echo "<td class=\"row2\"><input type=\"radio\" name=\"active\" value=\"1\">"._YES." &nbsp;&nbsp;"
	."<input type=\"radio\" name=\"active\" value=\"0\" checked>"._NO."</td>\n";
	echo "</tr>\n";
}
/*
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._ONHOME."</b></td>\n";
if ($onhome == 1) {
	echo "<td  class=\"row2\"><input type=\"radio\" name=\"onhome\" value=\"1\" checked>"._YES." &nbsp;&nbsp;"
	."<input type=\"radio\" name=\"onhome\" value=\"0\">"._NO."</td>\n";
} else {
	echo "<td  class=\"row2\"><input type=\"radio\" name=\"onhome\" value=\"1\">"._YES." &nbsp;&nbsp;"
	."<input type=\"radio\" name=\"onhome\" value=\"0\" checked>"._NO."</td>\n";
}
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>"._HOMELINKS."</b></td>\n";
echo "<td class=\"row2\"><select name=\"homelinks\">\n";
for($i = 0; $i <= 10; $i ++) {
	$seld ="";
	if($i == $homelinks) { $seld =" selected"; }
	echo "<option value=\"$i\"".$seld.">$i</option>\n";
}
echo "</select></td>\n";
echo "</tr>\n";
*/
echo "<input type=\"hidden\" name=\"subup\" value=\"1\">";
echo "<tr><td class=\"row3\">&nbsp;</td><td class=\"row3\"><input type=\"submit\" name=\"submit\" value=\""._SAVECHANGES."\"> <input type=\"button\" value=\""._CANCEL."\" onclick=\"window.location='".$adm_modname.".php?f=".$adm_modname."&do=categories'\"></td></tr>";
echo "</table></form>";

include_once("page_footer.php");
?>