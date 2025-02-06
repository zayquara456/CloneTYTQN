<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$result = $db->sql_query("SELECT content FROM ".$prefix."_gentext WHERE textname='help' AND alanguage='$currentlang'");
list($content) = $db->sql_fetchrow($result);

if(isset($_POST['submit'])) {
	$content = strip_tags($content, "<a><b><i><u><font><br/><strong><em>");
	$content = $escape_mysql_string($_POST['content']);
	if($db->sql_numrows($result) > 0) {
		$db->sql_query("UPDATE ".$prefix."_gentext SET content='$content' WHERE textname='help' AND alanguage='$currentlang'");
	}else{
		$db->sql_query("INSERT INTO ".$prefix."_gentext (textname, content, alanguage) VALUES ('help', '$content', '$currentlang')");
	}
	updateadmlog($admin_ar[0], $adm_modname, "Quản lý ghi chú", "Thay đổi");
	header("Location: modules.php?f=$adm_modname&do=$do&bf");
	exit();
}

include("page_header.php");

echo "<form action=\"modules.php?f=$adm_modname&do=$do\" method=\"POST\"><table border=\"0\" align=\"center\" width=\"98%\" cellspacing=\"0\" cellpadding=\"3\" class=\"tableborder\">\n";
echo "<tr><td class=\"header\">Quản lý ghi chú</td></tr>";
echo "<tr>\n";
echo "<td>";
editor("content",$content);
echo "</td>\n";
echo "</tr>\n";
echo "<tr><td align=\"center\"><input type=\"submit\" name=\"submit\" value=\""._SAVECHANGES."\" class=\"button2\"></td></tr>";
echo "</table></form><br/>";

include("page_footer.php");

?>