<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

//if (defined('iS_RADMIN')) {
	include_once("page_header.php");
	
	$err_title = $title = $err_size = $bheight = $bwidth ="";
	if(isset($_POST['subup']) && $_POST['subup'] == 1) {
		$title = trim(stripslashes(resString($_POST['title'])));
		$bwidth = intval($_POST['bwidth']);
		$bheight= intval($_POST['bheight']);
		$absbn = intval($_POST['absbn']);
		$bntype = intval($_POST['bntype']);

		if(empty($title)) {
			$err_title = "<font color=\"red\">"._ERROR1."</font><br/>";
			$err = 1;
		}

		if($db->sql_numrows($db->sql_query("SELECT*FROM ".$prefix."_advertise_banners WHERE title='$title'")) > 0) {
			$err_title = "<font color=\"red\">"._ERROR6."</font><br/>";
			$err = 1;
		}

		if(empty($bwidth)) {
			$err_size = "<font color=\"red\">"._ERROR7."</font><br/>";
			$err = 1;
		}

		if(empty($bheight) && !$err) {
			$err_size = "<font color=\"red\">"._ERROR8."</font><br/>";
			$err = 1;
		}

		if(!$err) {
			$db->sql_query("INSERT INTO ".$prefix."_advertise_banners (bnid, title, alanguage, bwidth, bheight, abs, type) VALUES (NULL, '$title', '$currentlang', '$bwidth', '$bheight', '$absbn', '$bntype')");
			header("Location: modules.php?f=".$adm_modname."&do=$do&bf");
		}
	}

	echo "<script language=\"javascript\">\n";
	echo "	function check(f) {\n";
	echo "		if(f.title.value =='') {\n";
	echo "			alert('"._ERROR5."');\n";
	echo "			f.title.focus();\n";
	echo "			return false;\n";
	echo "		}	\n";
	echo "		if(f.bwidth.value =='') {\n";
	echo "			alert('"._ERROR7."');\n";
	echo "			f.bwidth.focus();\n";
	echo "			return false;\n";
	echo "		}	\n";
	echo "		if(f.bheight.value =='') {\n";
	echo "			alert('"._ERROR8."');\n";
	echo "			f.bheight.focus();\n";
	echo "			return false;\n";
	echo "		}	\n";
	echo "		f.submit.disabled = true;\n";
	echo "		return true;	\n";
	echo "	}	\n";
	echo "</script>	\n";
	ajaxload_content();

	echo "<div id=\"".$adm_modname."_main\"><form action=\"modules.php?f=$adm_modname&do=$do\" method=\"POST\" enctype=\"multipart/form-data\" onsubmit=\"return check(this);\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
	echo "<tr><td class=\"header\" colspan=\"2\">"._ADD_BANNERS."</td></tr>";
	echo "<tr>\n";
	echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._TITLE."</b></td>\n";
	echo "<td class=\"row3\">$err_title<input type=\"text\" name=\"title\" value=\"$title\" size=\"60\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._BNSIZES."</b></td>\n";
	echo "<td class=\"row3\">$err_size<input type=\"text\" name=\"bwidth\" value=\"$bwidth\" size=\"6\"> X <input type=\"text\" name=\"bheight\" value=\"$bheight\" size=\"6\"> (width - height)</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._ABSSIZE."</b></td>\n";
	echo "<td class=\"row3\"><input type=\"checkbox\" name=\"absbn\" value=\"1\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._BNTYPES."</b></td>\n";
	echo "<td class=\"row3\"><select name=\"bntype\">";
	$bntype_arr = array(_BNTYPES1,_BNTYPES2);
	for($i =0; $i < sizeof($bntype_arr); $i ++) {
		echo "<option value=\"$i\">$bntype_arr[$i]</option>";
	}
	echo "</select></td>\n";
	echo "</tr>\n";
	echo "<input type=\"hidden\" name=\"subup\" value=\"1\">";
	echo "<tr><td class=\"row3\">&nbsp;</td><td class=\"row3\"><input type=\"submit\" name=\"submit\" value=\""._ADD."\" class=\"input1\"></td></tr>";
	echo "</table></form><br/>";

	$result = $db->sql_query("SELECT bnid, title, counts, active, bwidth, bheight, abs FROM ".$prefix."_advertise_banners ORDER BY bnid DESC");
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
		echo "		if(f.f.value =='') {\n";
		echo "			f.f.focus();\n";
		echo "			return false;\n";
		echo "		}\n";
		echo "		f.submit.disabled = true; \n";
		echo "		return true;		\n";
		echo "	}	\n";
		echo "</script>\n";
		echo "<form action=\"modules.php?f=$adm_modname&do=$do\" name=\"frm\" id=\"frm\" method=\"POST\" onsubmit=\"return checkQuick(this);\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
		echo "<tr><td colspan=\"9\" class=\"header\">"._MNGBANNERS."</td></tr>";
		echo "<tr>";
		echo "<td class=\"row1sd\" width=\"10\"><input type=\"checkbox\" name=\"checkall\" onclick=\"javascript:check_uncheck();\" title=\""._CHECKALL."\"></td>\n";
		echo "<td class=\"row3sd\">"._TITLE."</td>\n";
		echo "<td class=\"row1sd\" align=\"center\" width=\"100\">"._METHOD."</td>\n";
		echo "<td class=\"row1sd\" align=\"center\" width=\"80\">"._BNSIZES."</td>\n";
		echo "<td class=\"row3sd\" align=\"center\" width=\"80\">"._COUNTS."</td>\n";
		echo "<td class=\"row1sd\" align=\"center\" width=\"80\">"._STATUS."</td>\n";
		echo "<td class=\"row3sd\" align=\"center\" width=\"30\">"._EDIT."</td>\n";
		echo "<td class=\"row1sd\" align=\"center\" width=\"30\">"._DELETE."</td>\n";
		echo "</tr>\n";
		while(list($bnid, $title, $counts, $active, $bwidth, $bheight, $absbn) = $db->sql_fetchrow($result)) {
			if($absbn == 1) {
				$absck = "*";
			} else {
				$absck ="";
			}
			if ($ajax_active == 1) {
				switch($active) {
					case 1: $active = "<a href=\"?f=".$adm_modname."&do=status_banners&id=$bnid&stat=0\" title=\""._DEACTIVATE."\" onclick=\"return aj_base_status($bnid,0,'$adm_modname','status_banners','');\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
					case 0: $active = "<a href=\"?f=".$adm_modname."&do=status_banners&id=$bnid&stat=1\" title=\""._ACTIVE."\" onclick=\"return aj_base_status($bnid,1,'$adm_modname','status_banners','');\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
				}
			}
			else {
				switch($active) {
					case 1: $active = "<a href=\"?f=$adm_modname&do=status_banners&id=$bnid&stat=0\" info=\""._DEACTIVATE."\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
					case 0: $active = "<a href=\"?f=$adm_modname&do=status_banners&id=$bnid&stat=1\" info=\""._ACTIVE."\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
				}
			}
			echo "<tr>\n";
			echo "<td class=\"row1\" width=\"10\"><input type=\"checkbox\" name=\"bnid[]\" value=\"$bnid\"></td>\n";
			echo "<td class=\"row3\">";
			if($counts > 0) {
				echo "<a href=\"?f=$adm_modname&do=viewadv&id=$bnid\" info=\""._VIEWADV."\"><b>$title</b></a>";
			} else {
				echo "<b>$title</b>";
			}
			echo "</td>\n";
			echo "<td class=\"row1\" align=\"center\">advertising($bnid)</td>\n";
			echo "<td class=\"row1\" align=\"center\">$bwidth x $bheight $absck</td>\n";
			echo "<td class=\"row3\" align=\"center\" width=\"80\">$counts</td>\n";
			echo "<td class=\"row1\" align=\"center\" width=\"80\">$active</td>\n";
			echo "<td class=\"row3\" align=\"center\" width=\"30\"><a href=\"?f=$adm_modname&do=edit_banners&id=$bnid\" info=\""._EDIT."\"><img border=\"0\" src=\"images/edit.png\"></a></td>\n";
			echo "<td class=\"row3\" align=\"center\" width=\"30\">";
			echo "<a href=\"?f=$adm_modname&do=del_banners&id=$bnid\" info=\""._DELETE."\" onclick=\"return confirm('"._DELETEASK1."');\"><img border=\"0\" src=\"images/delete.png\">";
			echo "</td>\n";
			echo "</tr>\n";
		}
		echo "<input type=\"hidden\" name=\"do\" value=\"quick_do_banners\">";
		echo "<tr><td colspan=\"8\" class=\"row1\"><select name=\"v\">";
		echo "<option value=\"\">&raquo; "._QUICKDO."</option>";
		echo "<option value=\"1\">&raquo; "._QUICKDO_1."</option>";
		echo "<option value=\"2\">&raquo; "._QUICKDO_2."</option>";
		echo "<option value=\"3\">&raquo; "._QUICKDO_3."</option>";
		echo "</select> <input type=\"submit\" name=\"submit\" value=\""._DOACTION."\"></td></tr>";
		echo "</table><br/>";
		OpenDiv();
		echo "* "._NOTES."";
		CloseDiv();
		echo "</div>";
	}

	include_once("page_footer.php");
//} else {
	//header("Location: body.php");
//	echo "Illegal File Access";
//}
?>