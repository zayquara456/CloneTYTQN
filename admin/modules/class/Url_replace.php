<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");
global $prefix;
$id = intval($_GET['id']);
if(isset($_POST['subup'])&& $_POST['subup'] == 1) {
	$url = $escape_mysql_string(trim($_POST['url']));
		$query = "UPDATE ".$prefix."_link_report SET url_replace='$url' WHERE id='$id'";
		//die( "UPDATE ".$prefix."_link_report SET url_replace='$url' WHERE id='$id'");
		$db->sql_query($query);
		echo "<font color='red'>Thêm địa chỉ link mới thành công</font>";
	
} else {
	$err_title = "";
	$title = "";
}

echo "<script language=\"javascript\">\n";
echo "	function check(f) {\n";
echo "		if(f.title.value =='') {\n";
echo "			alert('"._ERROR1."');\n";
echo "			f.title.focus();\n";
echo "			return false;\n";
echo "		}\n";
echo "		f.submit.disabled = true;\n";
echo "		return true;	\n";
echo "	}	\n";
echo "</script>	\n";
ajaxload_content();

echo "<div id=\"{$adm_modname}_main\"><form action=\"modules.php?f=$adm_modname&do=$do&id=$id\" method=\"POST\" onsubmit=\"return check(this);\">";
echo "<div id=\"pagecontent\">";
echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
echo "<tr><td colspan=\"2\" class=\"header\">Thêm địa chỉ link mới thay thế</td></tr>";
echo "<tr>\n";
echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>url thay thế</b></td>\n";
echo "<td class=\"row2\">$err_title<input type=\"text\" id=\"title\" name=\"url\" value=\"$url\" size=\"50\"></td>\n";
echo "</tr>\n";
echo "<input type=\"hidden\" name=\"subup\" value=\"1\">";

echo "<tr><td colspan=\"2\" align=\"center\" class=\"row4\"><input type=\"submit\" class=\"button2\" id=\"submit\" name=\"submit\" value=\""._ADD."\"></td></tr>";
echo "</table></form>";
echo "</div>";
?>