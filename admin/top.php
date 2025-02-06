<?php
define('CMS_ADMIN', true);
require_once("../config.php");
require_once("language/$currentlang/main.php");

if(defined('iS_ADMIN')) {
	echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";
	echo "<head>\n";
	echo "<meta http-equiv=\"Content-Language\" content=\"en-us\">\n";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n";
	echo "<title>top</title>\n";
	echo "<link href=\"styles/top.css\" rel=\"stylesheet\" type=\"text/css\">\n";
	echo "</head>\n";
	echo "<body bgcolor=\"#425780\">\n";
	echo "<table border=\"0\" width=\"100%\" cellpadding=\"5\" style=\"border-collapse: collapse\" height=\"28\">\n";
	echo "	<tr>\n";
	echo "		<td>"._HELLO." $admin_ar[0] - </td>\n";
	if($multilingual == 1) {
		echo "<td width=\"80\" align=\"right\">"._LANGUAGE.": </td><td align=\"right\" width=\"80\"><form action=\"top.php\" name=\"jump\" method=\"POST\"><select name=\"cat\" OnChange=\"top.location.href=jump.cat.options[selectedIndex].value\">\n";
		$handle=opendir(RPATH."language");
		echo "$handle";
		while ($file = readdir($handle)) {
			if (($file != ".") && ($file !="..")) {
				if (is_dir(RPATH."/language/$file")){
					if($currentlang == $file) { $seldalang =" selected"; } else { $seldalang =""; }
					echo "<option value=\"body.php?lang=$file\"$seldalang>$file</option>";
				}
			}
		}
		closedir($handle);
		echo "</select></form></td>\n";
	}
	echo "		<td align=\"right\" style=\"padding-right: 20px\" width=\"210\"><a target=\"_top\" href=\"body.php\" title=\""._MAINPAGE."\" class=\"catmenu1\">"._MAINPAGE."</a> || <a target=\"_blank\" href=\"../".url_sid("index.php",1)."\" title=\""._HOMEPAGE."\" class=\"catmenu1\">"._HOMEPAGE."</a> || <a href=\"logout.php\" target=\"_top\" title=\""._LOGOUT."\" onclick=\"return confirm('"._LOGOUTASK."');\" class=\"catmenu1\">"._LOGOUT."</a></td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "</body>\n";
	echo "</html>\n";
} else {
	header("Location: login.php");
}

?>