<?php
if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$result = $db->sql_query("SELECT content FROM ".$prefix."_gentext WHERE textname='intro' AND alanguage='$currentlang'");
list($content) = $db->sql_fetchrow($result);

if($_POST['submit'] !="") {
	$content = trim(stripslashes(resString($_POST['content'])));
	if($db->sql_numrows($result) > 0) {
		$db->sql_query("UPDATE ".$prefix."_gentext SET content='$content' WHERE textname='intro' AND alanguage='$currentlang'");
	}else{
		$db->sql_query("INSERT INTO ".$prefix."_gentext (textname, content, alanguage) VALUES ('intro', '$content', '$currentlang')");
	}	
	updateadmlog($admin_ar[0], $adm_modname, "Tin nhan khi dang ky", "Cap nhat");
	header("Location: modules.php?f=$adm_modname&do=$do&bf");
	exit();
}	

include("page_header.php");

echo "<form action=\"modules.php?f=$adm_modname&do=$do\" method=\"POST\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" class=\"tableborder\">\n";
echo "<tr><td class=\"header\">"._INTRODUCE."</td></tr>";
echo "<tr>\n";
echo "<td>";
editor("content",$content, "",400);
echo "</td>\n";
echo "</tr>\n";
echo "<input type=\"hidden\" name=\"alang\" value=\"$alang\">";
echo "<tr><td align=\"center\"><input type=\"submit\" name=\"submit\" value=\""._SAVECHANGES."\" class=\"button2\"></td></tr>";
echo "</table></form><br/>";

include_once("page_footer.php");

?>