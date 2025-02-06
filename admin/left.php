<?php

define('CMS_ADMIN', true);
require_once("../config.php");
require_once("language/".$currentlang."/menu.php");

if(defined('iS_ADMIN')) {
	echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";
	echo "<head>\n";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n";
	echo "<meta name=\"robots\" content=\"noindex,nofollow\" >\n";
	echo "<link href=\"styles/menu.css\" rel=\"stylesheet\" type=\"text/css\">\n";
	echo "<script language=\"javascript\" src=\"js/menu.js\"></script>\n";
	echo "</head>\n";
	echo "<body scroll=\"yes\">\n";
	$not_accept_mod = array("authors","blocks","modules","configuration","adminlog");
	echo "<table border=\"0\" width=\"100%\" cellpadding=\"0\" background=\"styles/header.gif\" style=\"border-collapse: collapse\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"center\" style=\"padding-top: 3px\" height=\"30\"><a href=\"javascript:slash_contractall()\" class=\"catmenu\"><font color=\"#FFFFFF\">"._CLOSE."</font> <img border=\"0\" src=\"images/bullet.gif\" alt=\"bullet\" align=\"absmiddle\"/></a> <a href=\"javascript:slash_expandall()\" class=\"catmenu\"><img  align=\"absmiddle\" alt=\"bullet\" border=\"0\" src=\"images/bullet1.gif\"> <font color=\"#FFFFFF\">"._OPEN."</font></a></td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<div class=\"sdmenu\">\n";


	$result = $db->sql_query("SELECT * FROM ".$prefix."_admin_menu where action = '1' ORDER BY weight");
	while(list($mid, $file_menu, $weight, $action) = $db->sql_fetchrow($result)) {
		if (file_exists("menus/adm_".$file_menu.".php") && (defined('iS_RADMIN') || (checkPermAdm($file_menu) && !in_array($file_menu,$not_accept_mod)))) {
			include("menus/adm_".$file_menu.".php");
			if($menu_main != "" && $submenu !="") {
				echo "<span class=\"title\"><img src=\"images/expanded.gif\" class=\"arrow\" alt=\"-\" />$menu_main</span>\n";
				echo "      <div class=\"submenu\">\n";
				for($a =0; $a < sizeof($submenu); $a ++) {
					echo "".$submenu[$a]."";
				}
				echo "</div>";
			} else {
				echo "<div class=\"sd_menu\"><img  border=\"0\" src=\"images/collapsed.gif\">&nbsp;&nbsp;<a href=\"".$menu_main_link."\" target=\"_top\" class=\"catmenu\">$menu_main</a></div>";
			}
		}
	}

	echo "</div>\n";
	echo "</body></html>";
}else{
	header("Location: login.php");
}
?>