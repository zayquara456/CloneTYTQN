<?php
if(!defined('CMS_ADMIN')) {
	die();
}

include("page_header.php");


$result = $db->sql_query("SELECT mid, title, custom_title, active, view  FROM ".$prefix."_modules WHERE alanguage='$currentlang' ORDER BY title");
if($db->sql_numrows($result) > 0) {
	ajaxload_content();

	
	echo "<div id=\"".$adm_modname."_main\">";
	echo "<div id=\"pagecontent\">";
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"5\" class=\"tableborder\">\n";
	echo "<tr><td colspan=\"5\" class=\"header\">"._MODTITLE."</td></tr>";
	echo "<tr>\n";
	echo "<td class=\"row1sd\"><b>"._NAME."</b></td>\n";
	echo "<td class=\"row3sd\" align=\"center\"><b>"._CUSTOM_TITLE."</b></td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"100\"><b>"._VIEW."</b></td>\n";
	echo "<td class=\"row3sd\" align=\"center\" width=\"60\"><b>"._STATUS."</b></td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"30\"><b>"._EDIT."</b></td>\n";
	echo "</tr>\n";
	$i =0;
	while(list($mid, $title, $custom_title, $active, $view) = $db->sql_fetchrow($result)) {
		if($ajax_active == 1) {
			switch($active) {
				case 1: $active = "<a href=\"?f=$adm_modname&do=status&id=$mid&stat=0\" title=\""._DEACTIVATE."\" onclick=\"return aj_base_status($mid,0,'$adm_modname','','');\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
				case 0: $active = "<a href=\"?f=$adm_modname&do=status&id=$mid&stat=1\" title=\""._ACTIVE."\" onclick=\"return aj_base_status($mid,1,'$adm_modname','','');\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
			}
		} else {
			switch($active) {
				case 1: $active = "<a href=\"?f=$adm_modname&do=status&id=$mid&stat=0\" title=\""._DEACTIVATE."\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
				case 0: $active = "<a href=\"?f=$adm_modname&do=status&id=$mid&stat=1\" title=\""._ACTIVE."\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
			}
		}

		if($title == $Home_Module) {
			$css1 = $css2 = $css3 = $css4 = $css5 =" rowst";
			$active = "<img border=\"0\" src=\"images/view.png\" title=\""._ACTIVE."\">";
		}else{ $css1 = $css2 = $css3 = $css4 = $css5 ="row3"; }

		switch($view) {
			case 0: $view = _ALL; break;
			case 1: $view = _MVADMIN; break;
		}
		echo "<tr>\n";
		echo "<td class=\"$css1\"><b>$title</b></td>\n";
		if($ajax_active == 1) {
			echo "<td align=\"center\" class=\"$css2\" id=\"".$adm_modname."_title_edit_".$mid."\"><a href=\"?f=$adm_modname&do=edit&id=$mid\" title=\""._QUICK_EDIT."\" onclick=\"return show_edit_title($mid,'$custom_title','$adm_modname',20,'"._SAVECHANGES."','');\">$custom_title</a></td>\n";
		} else {
			echo "<td align=\"center\" class=\"$css2\"><a href=\"?f=$adm_modname&do=edit&id=$mid\" info=\""._EDIT."\">$custom_title</a></td>\n";
		}
		echo "<td align=\"center\" class=\"$css3\">$view</td>\n";
		echo "<td align=\"center\" class=\"$css4\">$active</td>\n";
		echo "<td align=\"center\" width=\"30\" class=\"$css5\"><a href=\"?f=$adm_modname&do=edit&id=$mid\" info=\""._EDIT."\"><img border=\"0\" src=\"images/edit.png\"></a></td>\n";
		echo "</tr>\n";
		$i ++;
	}
	echo "</table></div><br/>";

	OpenDiv();
	echo "* "._NOTES."";
	CloseDiv();
	echo "</div>\n";
}

include_once("page_footer.php");
?>