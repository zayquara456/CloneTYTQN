<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$id = intval($_GET['id']);
$result = $db->sql_query("SELECT title, email FROM ".$prefix."_contact_part WHERE id='$id' AND alanguage='$currentlang'");
if(empty($id) || $db->sql_numrows($result) != 1) {
	header("Location: modules.php?f=".$adm_modname.".&do=part");
}	

list($title, $email) = $db->sql_fetchrow($result);

$adm_pagetitle2 = _CTPART;
include("page_header.php");

$ds_title = $ds_email ="none";
if(isset($_POST['subup']) && $_POST['subup'] == 1) {
	$title = trim(stripslashes(resString($_POST['title'])));
	$email = trim(stripslashes(resString($_POST['email'])));
	
	if($title =="") {
		$ds_title = "";
		$err = 1;
	}	
	
	if($db->sql_numrows($db->sql_query("SELECT title FROM ".$prefix."_contact_part WHERE title='$title' AND id!='$id'")) > 0) {
		$ds_title = "";
		$err = 1;
	}
	
	if(!is_email($email)) {
		$ds_email = "";
		$err = 1;
	}		
	
	if(!$err) {
		$db->sql_query("UPDATE ".$prefix."_contact_part SET title='$title', email='$email' WHERE id='$id'");
		updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, ""._EDIT." "._CTPART."");
		header("Location: modules.php?f=".$adm_modname."&do=part");
	}	
}	

ajaxload_content();

echo "<form action=\"modules.php?f=$adm_modname&do=$do&id=$id\" method=\"POST\" onSubmit=\"return checkSubmit(this);\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" class=\"tableborder\">\n";
echo "<tr><td class=\"header\" colspan=\"2\">"._EDITPART."</td></tr>";
echo "<tr>\n";
echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>"._PARTNAME."</b></td>\n";
echo "<td class=\"row3\">".errorMess($ds_title, "title", _ERROR1)."<input type=\"text\" name=\"title\" value=\"$title\" size=\"40\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>Email</b></td>\n";
echo "<td class=\"row3\">".errorMess($ds_email, "email", _ERROR2)."<input type=\"text\" name=\"email\" value=\"$email\" size=\"40\"></td>\n";
echo "</tr>\n";
echo "<input type=\"hidden\" name=\"subup\" value=\"1\">\n";
echo "<tr><td colspan=\"2\" align=\"center\" class=\"row3\"><input type=\"submit\" name=\"submit\" value=\""._SAVECHANGES."\" class=\"button2\"></td></tr>";
echo "</table></form>";

include_once("page_footer.php");

?>