<?php

if(!defined('CMS_ADMIN')) die("Illegal File Access");

$adm_pagetitle2 = _BLRSS;

include("page_header.php");

ajaxload_content();

echo "<div id=\"".$adm_modname."_main\"><table align=\"center\" border=\"0\" width=\"\" cellspacing=\"0\" cellpadding=\"0\"><tr><td>\n";
$result = $db->sql_query("SELECT rid, sitename, headlinesurl FROM ".$prefix."_rss ORDER BY rid DESC");
if($db->sql_numrows($result) > 0) {
echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" class=\"tableborder\">\n";
echo "<tr>\n";
echo "<td class=\"header1\">"._SITENAME."</td>\n";
echo "<td class=\"header1\" align=\"center\">"._URL."</td>\n";
echo "<td class=\"header1\" align=\"center\" width=\"30\">"._EDIT."</td>\n";
echo "<td class=\"header1\" align=\"center\" width=\"30\">"._DELETE."</td>\n";
echo "</tr>\n";
$i =0;
while(list($rid, $site_name, $url) = $db->sql_fetchrow($result)) {
if($i%2 == 1) { $css = "row1"; }	else { $css ="row2"; }	

echo "<tr>\n";
echo "<td class=\"$css\"><b>$site_name</b></td>\n";
echo "<td align=\"center\" class=\"$css\"><a href=\"$url\" target=\"_blank\">$url</a></td>\n";
echo "<td align=\"center\" width=\"30\" class=\"$css\"><a href=\"?f=$adm_modname&do=edit_rss&id=$rid\" title=\""._EDIT."\"><img border=\"0\" src=\"images/edit.png\"></a></td>\n";
if($ajax_active == 0) {
	echo "<td align=\"center\" width=\"30\" class=\"$css\"><a href=\"?f=$adm_modname&do=delete_rss&id=$rid\" title=\""._DELETE."\" onclick=\"return confirm('"._DELETEASK1."');\"><img border=\"0\" src=\"images/delete.png\"></td>\n";
} else {
	echo "<td align=\"center\" width=\"30\" class=\"$css\"><a href=\"?f=$adm_modname&do=delete_rss&id=$rid\" title=\""._DELETE."\" onclick=\"return aj_base_delete($rid,'$adm_modname','"._DELETEASK1."','delete_rss');\"><img border=\"0\" src=\"images/delete.png\"></td>\n";
}	
echo "</tr>\n";
$i ++;	
}
echo "</table><br/>";	
}

$err_site = $site_name = $err_url = $url ="";
if(isset($_POST['submit']) && $_POST['submit'] !="") {
	$site_name = trim(stripslashes(resString($_POST['sitename'])));
	$url = trim(stripslashes(resString($_POST['url'])));
	
	if (empty($site_name)) {
		$err_site = "<font color=\"red\">"._ERROR4."</font><br/>";
		$err = 1;
	}	
	
	if (empty($url) || !preg_match_all('!^http(?:s)?://\w+(?:\-\w+)*(?:\.\w+(?:\-\w+)*)*\w{1}!i', $url, $matches) || ($matches[0] != $url)) {
		$err_url = "<font color=\"red\">"._ERROR5."</font><br/>";
		$err = 1;
	}
	
	if ($db->sql_numrows($db->sql_query("SELECT rid FROM ".$prefix."_rss WHERE headlinesurl='$url'")) > 0) {
		$err_url = "<font color=\"red\">"._ERROR5_1."</font><br/>";
		$err = 1;
	}		
	
	if (!$err) {
		$db->sql_query("INSERT INTO ".$prefix."_rss VALUES (NULL, '$site_name', '$url')");
		updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, ""._ADD." Rss");
		header("Location: modules.php?f=$adm_modname&do=$do&bf");
		exit;
	}	
}	

echo "<form action=\"modules.php?f=$adm_modname&do=$do\" method=\"POST\">\n";
echo "<table align=\"center\" border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" class=\"tableborder\">\n";
echo "<tr><td colspan=\"2\" class=\"header\">"._MODTITLE." &raquo; "._ADDRSS."</td></tr>";
echo "<tr>\n";
echo "<td width=\"30%\" align=\"right\" class=\"row1\"><b>"._SITENAME."</b></td>\n";
echo "<td  class=\"row2\">$err_site<input type=\"text\" name=\"sitename\" value=\"$site_name\" size=\"30\"></td></tr>";
echo "<tr>\n";
echo "<td width=\"30%\" align=\"right\" class=\"row1\"><b>"._URL."</b></td>\n";
echo "<td class=\"row2\">$err_url<input type=\"text\" name=\"url\" value=\"$url\" size=\"60\"></td></tr>";
echo "<tr><td class=\"row1\">&nbsp;</td><td class=\"row1\"><input type=\"submit\" name=\"submit\" value=\""._ADD."\" class=\"button2\"></td></tr>";
echo "</table></form>";
echo "</td></tr></table></div>\n";
include_once("page_footer.php");

?>