<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$adm_pagetitle2 = _ADDRESSMNG;

$result = $db->sql_query("SELECT content FROM ".$prefix."_rule WHERE alanguage='$currentlang'");
list($content) = $db->sql_fetchrow($result);

if(isset($_POST['submit']) && $_POST['submit'] !="") {
	$content = trim(stripslashes(resString($_POST['content'])));
	
	if($db->sql_numrows($result) > 0) {
		$db->sql_query("UPDATE ".$prefix."_rule SET content='$content' WHERE alanguage='$currentlang'");
	}else{
		$db->sql_query("INSERT INTO ".$prefix."_rule VALUES ('$content', '$currentlang')");
	}	
	updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, ""._SAVECHANGES." Address");
	header("Location: modules.php?f=$adm_modname&do=$do&bf");
	exit();
}	

include("page_header.php");

echo "<form action=\"modules.php?f=$adm_modname&do=$do\" method=\"POST\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" class=\"tableborder\">\n";
echo "<tr><td class=\"header\">"._ADDRESSMNG."</td></tr>";
echo "<tr>\n";
echo "<td>";
editor("content", $content,"",300);
echo "</td>\n";
echo "</tr>\n";
echo "<tr><td align=\"center\"><input type=\"submit\" name=\"submit\" value=\""._SAVECHANGES."\" class=\"button2\"></td></tr>";
echo "</table></form>";
include_once("page_footer.php");

?>