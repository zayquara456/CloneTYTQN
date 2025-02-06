<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

if (defined('iS_ADMIN')) {
	include_once("page_header.php");
	

	$result = $db->sql_query("SELECT bid, bkey, title, url, bposition, weight, active, blockfile, view, link, module FROM ".$prefix."_blocks WHERE blanguage='$currentlang' ORDER BY bposition, weight");
	if($db->sql_numrows($result) > 0) {
		echo "<script language=\"javascript\" type=\"text/javascript\">\n";
		echo "function check_uncheck(){\n";
		echo "	var f= document.frm;\n";
		echo "	if(f.checkall.checked){\n";
		echo "		CheckAllCheckbox(f,'id[]');\n";
		echo "	}else{\n";
		echo "		UnCheckAllCheckbox(f,'id[]');\n";
		echo "	}			\n";
		echo "}\n";
		echo "	function checkQuick(f) {\n";
		echo "		if(f.v.value =='') {\n";
		echo "			f.v.focus();\n";
		echo "			return false;\n";
		echo "		}\n";
		echo "		return true;		\n";
		echo "	}	\n";
		echo "</script>\n";
		ajaxload_content();

		echo "<div id=\"".$adm_modname."_main\"><form name=\"frm\" action=\"modules.php?f=".$adm_modname."\" method=\"POST\" onsubmit=\"return checkQuick(this);\"><table align=\"center\" border=\"0\" width=\"\" cellspacing=\"0\" cellpadding=\"3\" class=\"tableborder\">\n";
		echo "<tr><td colspan=\"10\" class=\"header\">"._MODTITLE."</td></tr>\n";
		echo "<tr>\n";
		echo "<td width=\"1%\" align=\"center\" class=\"row1sd\"><input type=\"checkbox\" name=\"checkall\" onclick=\"javascript:check_uncheck();\" title=\""._CHECKALL."\"></td>\n";
		echo "<td class=\"row3sd\">"._TITLE."</td>\n";
		echo "<td align=\"center\" width=\"100\" class=\"row1sd\">"._POSITION."</td>\n";
		echo "<td align=\"center\" width=\"50\" class=\"row3sd\">"._WEIGHT."</td>\n";
		echo "<td align=\"center\" width=\"50\" class=\"row1sd\">"._TYPE."</td>\n";
		echo "<td align=\"center\" width=\"60\" class=\"row3sd\">"._STATUS."</td>\n";
		echo "<td align=\"center\" width=\"80\" class=\"row1sd\">"._VIEW."</td>\n";
		echo "<td align=\"center\" class=\"row3sd\">"._DISPLAYAREA."</td>\n";
		echo "<td align=\"center\" width=\"30\" class=\"row1sd\">"._EDIT."</td>\n";
		echo "<td align=\"center\" width=\"30\" class=\"row3sd\">"._DELETE."</td>\n";
		echo "</tr>\n";
		$i =0;
		while(list($bid, $bkey, $title, $url, $bposition, $weight, $active, $blockfile, $view, $link, $bmodule) = $db->sql_fetchrow($result)) {
			switch($bposition) {
				case "l": $color ="#000000"; $bposition = "<img src=\"../images/center_r.gif\" border=\"0\" alt=\""._LEFT."\" alt=\""._LEFT."\" hspace=\"5\"> "._LEFT.""; break;
				case "r": $color ="#FF0000"; $bposition = ""._RIGHT." <img src=\"../images/center_l.gif\" border=\"0\" alt=\""._RIGHT."\" title=\""._RIGHT."\" hspace=\"5\">"; break;
				case "c": $color ="#FF00CC"; $bposition = "<img src=\"../images/center_l.gif\" border=\"0\" alt=\""._CENTERUP."\" title=\""._CENTERUP."\">&nbsp;"._CENTERUP."&nbsp;<img src=\"../images/center_r.gif\" border=\"0\" alt=\""._CENTERUP."\" title=\""._CENTERUP."\">"; break;
				case "d": $color ="#0000FF"; $bposition = "<img src=\"../images/center_l.gif\" border=\"0\" alt=\""._CENTERDOWN."\" title=\""._CENTERDOWN."\">&nbsp;"._CENTERDOWN."&nbsp;<img src=\"../images/center_r.gif\" border=\"0\" alt=\""._CENTERDOWN."\" title=\""._CENTERDOWN."\">"; break;
			}

			switch($bkey) {
				case 0: $bkey = "File"; break;
				case 1: $bkey = "HTML"; break;
				case 2: $bkey = "RSS/RDF"; break;
			}

			if($ajax_active == 1) {
				switch($active) {
					case 1: $active = "<a href=\"?f=$adm_modname&do=status&id=$bid&stat=0\" title=\""._DEACTIVATE."\" onclick=\"return aj_base_status($bid,0,'$adm_modname','','');\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
					case 0: $active = "<a href=\"?f=$adm_modname&do=status&id=$bid&stat=1\" title=\""._ACTIVE."\" onclick=\"return aj_base_status($bid,1,'$adm_modname','','');\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
				}
			} else {
				switch($active) {
					case 1: $active = "<a href=\"?f=$adm_modname&do=status&id=$bid&stat=0\" info=\""._DEACTIVATE."\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
					case 0: $active = "<a href=\"?f=$adm_modname&do=status&id=$bid&stat=1\" info=\""._ACTIVE."\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
				}
			}

			$bmodule_arr = @explode("|",$bmodule);
			$bmoduleds ="";
			if(!in_array("all",$bmodule_arr) && !in_array("home",$bmodule_arr)) {
				for($i =0; $i < sizeof($bmodule_arr); $i ++) {
					$bmoduleds .= "<option>".$listmods_name[$bmodule_arr[$i]]."</option>";
				}
			} else if (in_array("all",$bmodule_arr)) {
				$bmoduleds .= "<option>"._ALL."</option>";
			} else if (!in_array("all",$bmodule_arr) && in_array("home",$bmodule_arr)) {
				$bmoduleds .= "<option>"._HOMEPAGE."</option>";
			}

			switch($view) {
				case 0: $view = _ALL; break;
				case 1: $view = _MVADMIN; break;
			}

			echo "<tr>\n";
			echo "<td align=\"center\" class=\"row1\"><input type=\"checkbox\" name=\"id[]\" value=\"$bid\"></td>\n";
			if($ajax_active == 1) {
				echo "<td class=\"row3\" id=\"".$adm_modname."_title_edit_".$bid."\"><a href=\"?f=$adm_modname&do=edit&id=$bid\" title=\""._QUICK_EDIT."\" onclick=\"return show_edit_title($bid,'$title','$adm_modname',20,'"._SAVECHANGES."','');\"><b>$title</b></a></td>\n";
			} else {
				echo "<td class=\"row3\"><a href=\"?f=$adm_modname&do=edit&id=$bid\"><b>$title</b></a></td>\n";
			}
			echo "<td align=\"center\" class=\"row1\">$bposition</td>\n";
			echo "<td align=\"center\" class=\"row3\"><input type=\"text\" name=\"poz[$bid]\" value=\"$weight\" maxlength=\"2\" style=\"text-align: center; width: 30px; color: $color\"></td>\n";
			echo "<td align=\"center\" class=\"row1\">$bkey</td>\n";
			echo "<td align=\"center\" class=\"row3\">$active</td>\n";
			echo "<td align=\"center\" class=\"row1\">$view</td>\n";
			echo "<td align=\"center\" class=\"row3\"><select style=\"width: 100px\">$bmoduleds</select></td>\n";
			echo "<td align=\"center\" width=\"30\" class=\"row1\"><a href=\"?f=$adm_modname&do=edit&id=$bid\" info=\""._EDIT."\"><img border=\"0\" src=\"images/edit.png\"></a></td>\n";
			if($ajax_active == 1) {
				echo "<td align=\"center\" width=\"30\" class=\"row3\"><a href=\"?f=$adm_modname&do=delete&id=$bid\" title=\""._DELETE."\" onclick=\"return aj_base_delete($bid,'$adm_modname','"._DELETEASK."','','');\"><img border=\"0\" src=\"images/delete.png\"></td>\n";
			} else {
				echo "<td align=\"center\" width=\"30\" class=\"row3\"><a href=\"?f=$adm_modname&do=delete&id=$bid\" info=\""._DELETE."\" onclick=\"return confirm('"._DELETEASK."');\"><img border=\"0\" src=\"images/delete.png\"></td>\n";
			}
			echo "</tr>\n";
			$i ++;
		}
		echo "<input type=\"hidden\" name=\"do\" value=\"quick_do\">";
		echo "<tr><td colspan=\"10\" class=\"row3\"><select name=\"v\">";
		echo "<option value=\"\">&raquo; "._QUICKDO."</option>";
		echo "<option value=\"1\">&raquo; "._QUICKDO_1."</option>";
		echo "<option value=\"2\">&raquo; "._QUICKDO_2."</option>";
		echo "<option value=\"3\">&raquo; "._QUICKDO_3."</option>";
		echo "<option value=\"4\">&raquo; "._QUICKDO_4."</option>";
		echo "</select> <input type=\"submit\" class=\"button2\" value=\""._DOACTION."\">&nbsp;<a href=\"modules.php?f=".$adm_modname."&do=add\"><input type=\"button\" class=\"button2\" value=\""._BLOCKSADD."\" onclick=\"window.location='modules.php?f=".$adm_modname."&do=add'\"></a></td></tr>";
		echo "</table></form></div>";

	}else{
		OpenDiv();
		echo "<center>"._NOBLOCKSINSTALL."</center>";
		echo "<META HTTP-EQUIV=\"refresh\" content=\"5;URL=modules.php?f=".$adm_modname."&do=add\">";
		CLoseDiv();
	}

	include_once("page_footer.php");
} else {
	header("Location: body.php");
}

?>