<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");
include_once("page_header.php");
$err_no_file = $err_no_db = '';
$err = 0;
if (isset($_POST['subup'])) {
	if (!is_uploaded_file($_FILES['file']['tmp_name'])) {
		$err = 1;
		$err_no_file = "<font color=\"red\">"._DATABASE_ERROR_NO_FILE."</font><br />";
	}
	if (!isset($_POST['dbname']) || empty($_POST['dbname'])) {
		$err = 1;
		$err_no_db = "<font color=\"red\">"._DATABASE_ERROR_NO_DB."</font><br />";
	}
	if (!$err) {
		$dbname = $escape_mysql_string($_POST['dbname']);
		$upload = new Upload("file", "$path_upload/data", $maxsize_up, $adm_modname);
		$filename = $upload->send();
		$filename = RPATH."$path_upload/data/$filename";
		$query = file($filename);	
		$db->sql_query("CREATE DATABASE IF NOT EXISTS $dbname");
		$restoreDB = new sql_db($dbhost, $dbuname, $dbpass, $dbname);
		for ($i = 0; $i < count($query); $i++) {
			$restoreDB->sql_query($query[$i]);
		}
		@unlink($filename);
		$err_no_file = _DATABASE_RESTORED."<br />";
	}
} elseif ((isset($_GET['stat'])) && ($_GET['stat'] == 'done')) {
	$err_no_file = _DATABASE_BACKED_UP."<br />";
}
echo "<script language=\"javascript\">\n";
echo "	function check(f) {\n";
echo "		if(f.file.value == '') {\n";
echo "			alert('"._DATABASE_ERROR_NO_FILE."');\n";
echo "			f.file.focus();\n";
echo "			return false;\n";
echo "		}\n";
echo "		if(f.dbname.value == '') {\n";
echo "			alert('"._DATABASE_ERROR_NO_DB."');\n";
echo "			f.dbname.focus();\n";
echo "			return false;\n";
echo "		}\n";
echo "		f.submit.disabled = true;\n";
echo "		return true;\n";
echo "	}\n";
echo "</script>\n";
echo "<form action=\"modules.php?f=$adm_modname\" method=\"POST\" enctype=\"multipart/form-data\" onsubmit=\"return check(this);\">\n";
echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
echo "<tr><td colspan=\"2\" class=\"header\">"._DATABASE_BACKUP_RESTORE."</td></tr>";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._DATABASE_BACKUP_FILE."</b></td>\n";
echo "<td class=\"row2\">$err_no_file<input type=\"file\" id=\"file\" name=\"file\" size=\"50\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._DATABASE_RESTORE_TO_DB."</b></td>\n";
echo "<td class=\"row2\">$err_no_db<input type=\"text\" id=\"dbname\" name=\"dbname\" size=\"50\"></td>\n";
echo "</tr>\n";
echo "<input type=\"hidden\" name=\"subup\" value=\"1\">";
echo "<tr><td colspan=\"2\" align=\"center\" class=\"row1\">\n";
echo "<input type=\"hidden\" name=\"subup\" value=\"1\">\n";
echo "<input type=\"submit\" id=\"submit\" name=\"submit\" value=\""._DATABASE_RESTORE."\">\n";
echo "<input type=\"button\" value=\""._DATABASE_BACKUP."\" onclick=\"window.location.href='modules.php?f=$adm_modname&do=backup'\">\n";
echo "</td></tr>\n";
echo "</table></form>\n";
include_once("page_footer.php");
?>