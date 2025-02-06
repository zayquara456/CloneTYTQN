<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$err_title= $err_cat= "";
$id = intval($_GET['id']);
$result = $db->sql_query("SELECT title, cityid FROM ".$prefix."_district WHERE id='$id'");
if(empty($id) || $db->sql_numrows($result) != 1) {
	header("Location: ".$adm_modname.".php");
	exit;
}	

list($title,$cityid) = $db->sql_fetchrow($result);

if(isset($_POST['subup']) && $_POST['subup'] == 1) {
	$title = nospatags($_POST['title']);
	$cityid = $_POST['cityid'];
	
	if($title =="") {
		$err_title = "<font color=\"red\">"._ERROR1."</font><br>";
		$err = 1;
	}
	
	if($db->sql_numrows($db->sql_query("SELECT title FROM ".$prefix."_district WHERE title='$title' AND alanguage='$currentlang' AND id!='$id'")) > 0) {
		$err_title = "<font color=\"red\">"._ERROR2."</font><br>";
		$err = 1;
	}	
	if ($cityid == 0) {
		$err_cat = "<font color=\"red\">"._ERROR1_1."</font><br>";
		$err = 1;
	}
	if(!$err) {
		$db->sql_query("UPDATE ".$prefix."_district SET title='$title', cityid='$cityid' WHERE id='$id'");
		updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _EDIT);
		header("Location: modules.php?f=".$adm_modname."&id=$cityid");
	}	
}	

include("page_header.php");

echo "<br><form method=\"POST\" onsubmit=\"if(this.title.value=='') { this.title.focus(); return false; }\"><table border=\"0\" align=\"center\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
echo "<tr><td class=\"header\" colspan=\"2\">"._MODTITLE." &raquo; "._EDITDISTRICT."</td></tr>";
echo "<td align=\"right\" class=\"row1\"><b>"._CITY."</b></td>\n";
$result_cat = $db->sql_query("SELECT id, title FROM ".$prefix."_city WHERE alanguage='$currentlang' ORDER BY weight");
echo "		<td class=\"row2\">$err_cat<select name=\"cityid\">";
echo "<option name=\"cityid\" value=\"0\">"._CITY."</option>";
while(list($id, $titlecity) = $db->sql_fetchrow($result_cat)) {
	if($id == $cityid) {$seld =" selected"; }else{ $seld ="";}
	echo "<option value=\"$id\" $seld style=\"font-weight: bold\">$titlecity</option>";
}
echo "</select></td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._DISTRICTNAME."</b></td>\n";
echo "<td class=\"row3\">$err_title<input type=\"text\" name=\"title\" value=\"$title\" size=\"30\"> <input type=\"hidden\" name=\"subup\" value=\"1\"><input type=\"submit\" name=\"submit\" value=\""._SAVECHANGES."\" class=\"input1\"></td>\n";
echo "</tr>\n";
echo "</table></form><br>";

include("page_footer.php");

?>