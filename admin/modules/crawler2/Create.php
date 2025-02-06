<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");
$err_title = $err_cat = "";
if (isset($_POST['submit'])) {
	$title = $escape_mysql_string(trim($_POST['title']));
	$filedata = $_POST['filedata'];
	$data = generate_code(8).".cl";
	$data_url="modules/crawler/data/".$data;
	$source = isset($_POST['source']) ? $escape_mysql_string(trim($_POST['source'])) : '';
	$err = 0;
	if($title =="") {
		$err_title = "<font color=\"red\">"._ERROR1."</font><br/>";
		$err = 1;
	}
	if(!$err) {
		file_put_contents($data_url, $filedata);
		$query = $db->sql_query("INSERT {$prefix}_ngrab_filter (title, source, data, alanguage, time) VALUES ('$title', '$source', '$data', '$currentlang', ".time().")");
		updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _NEWS_EDIT_NEWS);
		header("Location: modules.php?f=".$adm_modname."&msg=insert");
	}
}



include_once("page_header.php");

echo "<form action=\"modules.php?f=$adm_modname&do=$do\" method=\"POST\" enctype=\"multipart/form-data\">";
echo "
<div id=\"pagecontent\">
	<div class=\"ctrl-header\">
		<div><span id=\"ctl10_lblTitle\">"._CREATE_FILTER."</span></div>
	</div>
	<div class=\"ctrl-content\">
		<div class=\"ctrl-content-list\">";
echo "<table class=\"tableborder\" border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
echo "<td width=\"120px\" align=\"right\" class=\"row1\"><b>"._TITLE."</b></td>\n";
echo "<td class=\"row2\"><input type=\"text\" name=\"title\" value=\"\" size=\"100\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._HOMETEXT."</b></td>\n";
echo "<td class=\"row2\" colspan=\"2\">";
echo "<textarea cols=\"80\" rows=\"15\" name=\"filedata\"></textarea>";
echo "</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._SOURCE."</b></td>\n";
echo "<td class=\"row2\"><input type=\"text\" name=\"source\" value=\"\" size=\"60\" maxlength=\"253\"></td>\n";
echo "</tr>\n";
echo "<tr><td >&nbsp;</td><td ><input type=\"hidden\"  name=\"csrf\" value=\"$key\" /><input type=\"submit\" name=\"submit\" value=\""._SAVECHANGES."\" class=\"button2\"></td></tr>";

echo "</table>";
echo "	</div>
		<div class=\"ctrl-footer\"></div>
</div>
<div class=\"cl\"></div>
</div>
</div>";
echo "</form>";

include_once("page_footer.php");
?>