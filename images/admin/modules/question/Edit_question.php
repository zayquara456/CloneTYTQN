<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$id = intval(isset($_GET['id']) ? $_GET['id'] : $_POST['id']);

$query = "SELECT id, catid, title, content, time, name, email,hits";
$table = "{$prefix}_question";
$query .= ", time";
$query .= " FROM $table WHERE id=$id";
$result = $db->sql_query($query);
//if($db->sql_numrows($result) != 1) header("Location: modules.php?f=$adm_modname");
list($id, $catid, $title, $content, $time, $name, $email,$hits) = $db->sql_fetchrow($result);

$err_title = $err_cat = "";
if (isset($_POST['submit'])) {
	$title = $escape_mysql_string(trim($_POST['title']));
	$catid = intval($_POST['catid']);
	$content = $escape_mysql_string(trim($_POST['content']));
	$guid="index.php?f=question&do=detail&id=$id";

	$err = 0;
	if($title =="") {
		$err_title = "<font color=\"red\">"._ERROR1."</font><br>";
		$err = 1;
	}
	else
	{
		$permalink=url_optimization(trim($title));
	}
	if($catid == 0) {
		$err_cat = "<font color=\"red\">"._ERROR1_1."</font><br>";
		$err = 1;
	}
	if(!$err) {
			$query = "UPDATE $table SET catid=$catid, title='$title', permalink='$permalink',  guid='$guid', content='$content' WHERE id=$id";
			$db->sql_query($query);
		//updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _CAMNANG_EDIT_CAMNANG);
		header("Location: modules.php?f=".$adm_modname."&do=question");
	}
}

$title = str_replace('"',"''",$title);

include_once("page_header.php");

echo "<form action=\"modules.php?f=$adm_modname&do=$do&id=$id\" method=\"POST\" enctype=\"multipart/form-data\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" class=\"tableborder\">\n";
echo "<tr><td class=\"header\" colspan=\"2\">"._EDITCAMNANG."</td></tr>";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._TITLE."</b></td>\n";
echo "<td class=\"row3\">$err_title<input type=\"text\" name=\"title\" value=\"$title\" size=\"70\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._INCAT."</b></td>\n";
$result_cat = $db->sql_query("SELECT catid, title FROM ".$prefix."_question_cat WHERE parent='0' and alanguage='$currentlang' ORDER BY weight");
echo "		<td class=\"row3\">$err_cat<select name=\"catid\">";
echo "<option name=\"catid\" value=\"0\">"._INCAT0."</option>";
$listcat ="";
while(list($cat_id, $titlecat) = $db->sql_fetchrow($result_cat)) {
	if($cat_id == $catid) {$seld =" selected"; }else{ $seld ="";}
	$listcat .= "<option value=\"$cat_id\"$seld style=\"font-weight: bold\">- $titlecat</option>";
	$listcat .= subcat($cat_id,"-",$catid, "");
}
echo $listcat;
echo "</select></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._BODYTEXT."</b></td>\n";
echo "<td class=\"row3\"><textarea cols=\"160\" rows=\"8\" name=\"content\">$content</textarea></td>\n";
echo "</tr>\n";

echo "<tr><td class=\"row3\">&nbsp;</td><td class=\"row3\"><input type=\"submit\" name=\"submit\" value=\""._SAVECHANGES."\" class=\"button2\"></td></tr>";
echo "</table></form><br>";

include_once("page_footer.php");
?>