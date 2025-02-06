<?php

if(!defined('CMS_ADMIN')) die("Illegal File Access");

$adm_pagetitle2 = _EDITBLRSS;

$id = intval(isset($_GET['id']) ? $_GET['id'] : $_POST['id']);

$result = $db->sql_query("SELECT sitename, headlinesurl FROM ".$prefix."_rss WHERE rid='$id'");
if(empty($id) || $db->sql_numrows($result) != 1) {
	header("Location: ".$adm_modname.".php?do=Setup_Rss");
	die();
}	

include("page_header.php");

list($sitename, $url) = $db->sql_fetchrow($result);
$err_site = $err_url ="";
if(isset($_POST['submit']) && $_POST['submit'] !="") {
	$sitename = trim(stripslashes(resString($_POST['sitename'])));
	$url = trim(stripslashes(resString($_POST['url'])));
	
	if (empty($sitename)) {
		$err_site = "<font color=\"red\">"._ERROR4."</font><br/>";
		$err = 1;
	}	
	
	if (empty($url) || !preg_match_all('!^http(?:s)?://\w+(?:\-\w+)*(?:\.\w+(?:\-\w+)*)*\w{1}!i', $url, $matches) || ($matches[0] != $url)) {
		$err_url = "<font color=\"red\">"._ERROR5."</font><br/>";
		$err = 1;
	}	
	
	if($db->sql_numrows($db->sql_query("SELECT rid FROM ".$prefix."_rss WHERE headlinesurl='$url' AND rid!='$id'")) > 0) {
		$err_url = "<font color=\"red\">"._ERROR5_1."</font><br/>";
		$err = 1;
	}		
	
	if(!$err) {
		$db->sql_query("UPDATE ".$prefix."_rss SET sitename='$sitename', headlinesurl='$url' WHERE rid='$id'");
		updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, ""._EDIT." Rss");
		header("Location: modules.php?f=".$adm_modname."&do=Setup_Rss");
		exit;
	}	
}	

echo "<form action=\"modules.php?f=$adm_modname&do=$do&id=$id\" method=\"POST\">\n";
echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" class=\"tableborder\">\n";
echo "<tr>\n";
echo "<td width=\"30%\" align=\"right\" class=\"row1\"><b>"._SITENAME."</b></td>\n";
echo "<td class=\"row2\">$err_site<input type=\"text\" name=\"sitename\" value=\"$sitename\" size=\"30\"></td></tr>";
echo "<tr>\n";
echo "<td width=\"30%\" align=\"right\" class=\"row1\"><b>"._URL."</b></td>\n";
echo "<td class=\"row2\">$err_url<input type=\"text\" name=\"url\" value=\"$url\" size=\"50\"></td></tr>";
echo "<input type=\"hidden\" name=\"id\" value=\"$id\">";
echo "<tr><td class=\"row1\">&nbsp;</td><td class=\"row1\"><input type=\"submit\" name=\"submit\" value=\""._SAVECHANGES."\" class=\"button2\"></td></tr>";
echo "</table></form>";
include_once("page_footer.php");

?>