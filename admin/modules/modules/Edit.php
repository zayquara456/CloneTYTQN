<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$id = intval(isset($_GET['id']) ? $_GET['id'] : $_POST['id']);

$result = $db->sql_query("SELECT title, custom_title, seo_title, seo_description, seo_keyword, active, view, mindex FROM ".$prefix."_modules WHERE mid='$id'");
if(empty($id) || $db->sql_numrows($result) != 1) {
	header("Location: ".$adm_modname.".php");
	die();
}

list($title_mod, $custom_title,$title_seo, $description_seo, $keyword_seo, $active, $view, $mindex) = $db->sql_fetchrow($result);

include("page_header.php");

if(isset($_POST['submit']) && $_POST['submit'] !="") {
	$title = $escape_mysql_string(trim($_POST['title']));
	$view = intval($_POST['view']);
	$active = intval($_POST['active']);
	$mindex = intval($_POST['mindex']);
	$title_seo			= isset($_POST['title_seo']) ? $escape_mysql_string(trim($_POST['title_seo'])) : '';
	$description_seo		= isset($_POST['description_seo']) ? $escape_mysql_string(trim($_POST['description_seo'])) : '';
	$keyword_seo			= isset($_POST['keyword_seo']) ? $escape_mysql_string(trim($_POST['keyword_seo'])) : '';

	if($title =="") {$title = $title_mod; }

	if(!$err) {
		$db->sql_query("UPDATE ".$prefix."_modules SET custom_title='$title', seo_title='$title_seo', seo_description='$description_seo', seo_keyword='$keyword_seo', active='$active', view='$view', mindex='$mindex' WHERE mid='$id'");
		updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, ""._SAVECHANGES."");
		header("Location: modules.php?f=".$adm_modname."");
	}
}
echo "<div id=\"pagecontent\">";
echo "<form action=\"modules.php?f=$adm_modname&do=$do&id=$id\" method=\"POST\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
echo "<tr><td colspan=\"2\" class=\"header\">"._EDITMODULE."</td></tr>";
echo "<tr>\n";
echo "<td width=\"30%\" align=\"right\" class=\"row1\"><b>"._TITLE."</b></td>\n";
echo "<td class=\"row2\"><input type=\"text\" name=\"title\" value=\"$custom_title\" size=\"30\"></td>\n";
echo "</tr>\n";
$view_array = array(_ALL, _MVADMIN);
echo"<tr><td align=\"right\" class=\"row1\"><b>"._VIEWPRIV."</b></td><td class=\"row2\"><select name=\"view\">";
for($vi=0;$vi < sizeof($view_array);$vi++) {
	echo "<option value=\"$vi\"";
	if($view==$vi) { echo " selected"; }
	echo">$view_array[$vi]</option>";
}
echo "</select>"
."</td></tr>";
if($title_mod != $Home_Module) {
	if ($active == 1) {
		echo "<tr><td align=\"right\" class=\"row1\"><b>"._ACTIVE."?</b></td><td class=\"row2\"><input type=\"radio\" name=\"active\" value=\"1\" checked>"._YES." &nbsp;&nbsp;"
		."<input type=\"radio\" name=\"active\" value=\"0\">"._NO."</td></tr>";
	} elseif ($active == 0) {
		echo "<tr><td align=\"right\" class=\"row1\"><b>"._ACTIVE."?</b></td><td class=\"row2\"><input type=\"radio\" name=\"active\" value=\"1\">"._YES." &nbsp;&nbsp;"
		."<input type=\"radio\" name=\"active\" value=\"0\" checked>"._NO."</td></tr>";
	}
} else {
	echo "<input type=\"hidden\" name=\"active\" value=\"1\">";
}
echo "<tr>\n";
echo "<td width=\"30%\" align=\"right\" class=\"row1\"><b>"._MTHEME."</b></td>\n";
$mtheme_arr = array(_MTHEME1,_MTHEME2,_MTHEME3,_MTHEME4);
echo "<td class=\"row2\"><select name=\"mindex\">\n";
for($i =0; $i < sizeof($mtheme_arr); $i ++) {
	$seld ="";
	if($i == $mindex) { $seld =" selected"; }
	echo "<option value=\"$i\"".$seld.">$mtheme_arr[$i]</option>\n";
}
echo "</select></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._TITLE_SEO."</b></td>\n";
echo "<td class=\"row2\"><input type=\"text\" name=\"title_seo\" value=\"$title_seo\" size=\"60\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._DESCRIPTION_SEO."</b></td>\n";
echo "<td class=\"row2\"><textarea cols=\"58\" rows=\"3\" name=\"description_seo\">$description_seo</textarea></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._KEYWORD_SEO."</b></td>\n";
echo "<td class=\"row2\"><input type=\"text\" name=\"keyword_seo\" value=\"$keyword_seo\" size=\"40\"></td>\n";
echo "</tr>\n";
echo "<input type=\"hidden\" name=\"id\" value=\"$id\">";
echo "<tr><td class=\"row3\">&nbsp;</td><td class=\"row3\"><input type=\"submit\" name=\"submit\" value=\""._SAVECHANGES."\"> <input type=\"button\" value=\""._CANCEL."\" onclick=\"window.location='modules.php?f=".$adm_modname."'\"></td></tr>";
echo "</table></form></div>";

include_once("page_footer.php");
?>