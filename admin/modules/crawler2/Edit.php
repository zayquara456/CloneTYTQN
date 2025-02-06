<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$id = intval(isset($_GET['id']) ? $_GET['id'] : $_POST['id']);
//@chmod("../data/setting.php", 0777);

//$id = $_GET['id'];
$query = "SELECT title, alanguage, source, data, time, status FROM {$prefix}_ngrab_filter WHERE id=$id";
$result = $db->sql_query($query);
if($db->sql_numrows($result) != 1) header("Location: modules.php?f=$adm_modname");
list($title, $alanguage, $source, $data, $time, $status ) = $db->sql_fetchrow($result);
if(!empty($data))
{
	$data_url="modules/crawler/data/".$data."";
	@$filedata = file_get_contents($data_url,true);
}
else
{
	$filedata= "";
}

$title = str_replace('"',"''",$title);
$err_title = $err_cat = "";
if (isset($_POST['submit'])) {
	$title = $escape_mysql_string(trim($_POST['title']));
	$filedata = $_POST['filedata'];
	$data = $_POST['data'];
	if(empty($data))
	{
		$data= generate_code(8).".cl";
		$data_url= "modules/crawler/data/".$data;
	}
	$source = isset($_POST['source']) ? $escape_mysql_string(trim($_POST['source'])) : '';
	$err = 0;
	if($title =="") {
		$err_title = "<font color=\"red\">"._ERROR1."</font><br/>";
		$err = 1;
	}
	if(!$err) {
		file_put_contents($data_url, $filedata);
		$query = $db->sql_query("UPDATE {$prefix}_ngrab_filter SET title='$title', source='$source', data='$data' WHERE id=$id");
		updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _NEWS_EDIT_NEWS);
		header("Location: modules.php?f=".$adm_modname."&msg=update");
	}
}



include_once("page_header.php");

echo "<form action=\"modules.php?f=$adm_modname&do=$do&id=$id\" method=\"POST\" enctype=\"multipart/form-data\">";
echo "
<div id=\"pagecontent\">
	<div class=\"ctrl-header\">
		<div><span id=\"ctl10_lblTitle\">"._EDITNEWS."</span></div>
	</div>
	<div class=\"ctrl-content\">
		<div class=\"ctrl-content-list\">";
echo "<table class=\"tableborder\" border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
echo "<td width=\"120px\" align=\"right\" class=\"row1\"><b>"._TITLE."</b></td>\n";
echo "<td class=\"row2\"><input type=\"text\" name=\"title\" value=\"$title\" size=\"100\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._HOMETEXT."</b></td>\n";
echo "<td class=\"row2\" colspan=\"2\">";
echo "<textarea cols=\"80\" rows=\"15\" name=\"filedata\">$filedata</textarea>";
echo "</td>\n";
echo "</tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._SOURCE."</b></td>\n";
echo "<td class=\"row2\"><input type=\"text\" name=\"source\" value=\"$source\" size=\"60\" maxlength=\"253\"></td>\n";
echo "</tr>\n";
echo "<tr><td >&nbsp;</td><td >";
echo "<input type=\"hidden\"  name=\"csrf\" value=\"$key\" />";
echo "<input type=\"hidden\"  name=\"data\" value=\"$data\" />";
echo "<input type=\"submit\" name=\"submit\" value=\""._SAVECHANGES."\" class=\"button2\"></td></tr>";

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