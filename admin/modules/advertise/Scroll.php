<?php

if(!defined('CMS_ADMIN')) die("Illegal File Access");

include("page_header.php");

$page = intval(isset($_GET['page']) ? $_GET['page'] : (isset($_POST['page']) ? $_POST['page']:1));

$target = 1;
$err_title = $title = $err_links = $links = $err_img = $imgtext = $err_post ="";
if(isset($_POST['subup']) && $_POST['subup'] == 1) {
	$title = $escape_mysql_string(trim($_POST['title']));
	$links = $escape_mysql_string(trim($_POST['links']));
	$target = intval($_POST['target']);
	$imgtext = $escape_mysql_string(trim($_POST['imgtext']));
	$poz = intval($_POST['poz']);

	if(empty($title)) {
		$err_title = "<font color=\"red\">"._ERROR1."</font><br/>";
		$err = 1;
	}

	if(empty($links) || !preg_match('!^http(s)?://!i', $links)) {
		$err_links = "<font color=\"red\">"._ERROR2."</font><br/>";
		$err = 1;
	}

	if (!is_uploaded_file($_FILES['userfile']['tmp_name'])) {
		$err_img = "<font color=\"red\">"._ERROR3."</font><br/>";
		$err = 1;
	}

	if(!$err) {
		if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
			$ext_allow = array("jpg","jpeg","gif","png", "swf");
			$upload = new Upload("userfile", "$path_upload/adv", $maxsize_up, "adv");
			$images = $upload->send();
		}
		$weight = WeightMax("advertise","",$position);
		$db->sql_query("INSERT INTO ".$prefix."_advertise_scroll (id, poz, title, links, time, target, images, imgtext, alanguage, weight) VALUES (NULL, '$poz', '$title', '$links', '".time()."', '$target', '$images', '$imgtext', '$currentlang', '$weight')");
		fixweight_scroll();
		header("Location: modules.php?f=$adm_modname&do=$do");
	}
}

echo "<script language=\"javascript\">\n";
echo "	function check(f) {\n";
echo "		if(f.title.value =='') {\n";
echo "			alert('"._ERROR1."');\n";
echo "			f.title.focus();\n";
echo "			return false;\n";
echo "		}	\n";
echo "		if(f.links.value =='') {\n";
echo "			alert('"._ERROR2."');\n";
echo "			f.links.focus();\n";
echo "			return false;\n";
echo "		}	\n";
echo "		if(f.userfile.value =='') {\n";
echo "			alert('"._ERROR3."');\n";
echo "			f.userfile.focus();\n";
echo "			return false;\n";
echo "		}	\n";
echo "		if(f.bnid.value == 0) {\n";
echo "			alert('"._ERROR4."');\n";
echo "			f.bnid.focus();\n";
echo "			return false;\n";
echo "		}	\n";
echo "		f.submit.disabled = true;\n";
echo "		return true;	\n";
echo "	}	\n";
echo "</script>	\n";
ajaxload_content();

echo "<div id=\"".$adm_modname."_main\"><form action=\"modules.php?f=$adm_modname&do=$do&page=$page\" method=\"POST\" enctype=\"multipart/form-data\" onsubmit=\"return check(this);\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" class=\"tableborder\">\n";
echo "<tr><td class=\"header\" colspan=\"2\">"._ADD_ADV."</td></tr>";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._TITLE."</b></td>\n";
echo "<td class=\"row2\">$err_title<input type=\"text\" name=\"title\" value=\"$title\" size=\"60\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._URL."</b> (http://...)</td>\n";
echo "<td  class=\"row2\">$err_links<input type=\"text\" name=\"links\" value=\"$links\" size=\"60\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._IMAGEADV."</b></td>\n";
echo "<td class=\"row2\">$err_img<input type=\"file\" name=\"userfile\" size=\"50\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._IMGTEXT."</b></td>\n";
echo "<td class=\"row2\"><input type=\"text\" name=\"imgtext\" value=\"$imgtext\" size=\"70\" maxlength=\"253\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._NEWWINDOW."</b></td>\n";
if($target == 0) {
	echo "<td  class=\"row2\"><input type=\"radio\" name=\"target\" value=\"1\">"._YES." &nbsp;&nbsp;"
	."<input type=\"radio\" name=\"target\" value=\"0\" checked>"._NO."</td>\n";
} else {
	echo "<td  class=\"row2\"><input type=\"radio\" name=\"target\" value=\"1\" checked>"._YES." &nbsp;&nbsp;"
	."<input type=\"radio\" name=\"target\" value=\"0\">"._NO."</td>\n";
}
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._POSITION."</b></td>\n";
echo "<td class=\"row2\">$err_post<select name=\"poz\">";
$poz_arr = array(_LEFT,_RIGHT);
for($i =0; $i < 2; $i ++) {
	$seld ="";
	if($i == $poz) { $seld ="selected"; }
	echo "<option value=\"$i\"$seld>$poz_arr[$i]</option>";
}
echo "</select></td>\n";
echo "</tr>\n";
echo "<input type=\"hidden\" name=\"subup\" value=\"1\">";
echo "<tr class=\"row4\"><td></td><td><input type=\"submit\" name=\"submit\" value=\""._ADD."\" class=\"input1\"></td></tr>";
echo "</table></form><br/>";

echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
echo "<tr>\n";
echo "<tr><td colspan=\"9\" class=\"header\">"._VIEWADVSCROLL."</td></tr>";
$perpage = 20;
$offset = ($page-1) * $perpage;
$numsf = $db->sql_fetchrow($db->sql_query("SELECT COUNT(*) FROM ".$prefix."_advertise_scroll WHERE alanguage='$currentlang'"));
$total = ($numsf[0]) ? $numsf[0] : 1;
$result = $db->sql_query("SELECT  id, title, time, active, hits, weight, poz  FROM ".$prefix."_advertise_scroll WHERE alanguage='$currentlang' ORDER BY poz,weight LIMIT $offset, $perpage");
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
	echo "		if(f.fc.value =='') {\n";
	echo "			f.fc.focus();\n";
	echo "			return false;\n";
	echo "		}\n";
	echo "		f.submit.disabled = true; \n";
	echo "		return true;		\n";
	echo "	}	\n";
	echo "</script>\n";
	echo "<form name=\"frm\" method=\"POST\" action=\"modules.php?f=$adm_modname\" onsubmit=\"return checkQuick(this);\"><tr><td class=\"row3\" width=\"10\"><input type=\"checkbox\" name=\"checkall\" onclick=\"javascript:check_uncheck();\" title=\""._CHECKALL."\"></td>\n";
	echo "<td class=\"row1sd\">"._TITLE."</td>\n";
	echo "<td align=\"center\" width=\"50\" class=\"row3sd\">"._POSITION."</td>\n";
	echo "<td align=\"center\" width=\"50\" class=\"row3sd\">"._WEIGHT."</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"100\">"._DATESENT."</td>\n";
	echo "<td class=\"row3sd\" align=\"center\" width=\"80\">Clicks</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"80\">"._STATUS."</td>\n";
	echo "<td class=\"row3sd\" align=\"center\" width=\"30\">"._EDIT."</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"30\">"._DELETE."</td>\n";
	echo "</tr>\n";
	$cur_ar = array(_VND,_USD);
	$i =0;
	while(list($id, $title, $time, $active, $hits, $weight, $poz) = $db->sql_fetchrow($result)) {
		if ($ajax_active == 1) {
			switch($active) {
				case 1: $active = "<a href=\"modules.php?f=".$adm_modname."&do=status_scroll&id=$id&stat=0\" title=\""._DEACTIVATE."\" onclick=\"return aj_base_status($id,0,'$adm_modname','status_scroll','');\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
				case 0: $active = "<a href=\"modules.php?f=".$adm_modname."&do=status_scroll&id=$id&stat=1\" title=\""._ACTIVE."\" onclick=\"return aj_base_status($id,1,'$adm_modname','status_scroll','');\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
			}
		}
		else {
			switch($active) {
				case 1: $active = "<a href=\"modules.php?f=$adm_modname&do=status_scroll&id=$id&stat=0\" info=\""._DEACTIVATE."\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
				case 0: $active = "<a href=\"modules.php?f=$adm_modname&do=status_scroll&id=$id&stat=1\" info=\""._ACTIVE."\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
			}
		}

		switch($poz) {
			case 0: $poz = _LEFT; break;
			case 1: $poz = _RIGHT; break;
		}

		echo "<tr>\n";
		echo "<td class=\"row1\"><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td>";
		echo "<td class=\"row3\"><a href=\"modules.php?f=advertise&do=edit_scroll&id=$id\" info=\""._EDIT."\"><b>$title</b></a></td>\n";
		echo "<td class=\"row3\" align=\"center\">$poz</td>\n";
		echo "<td class=\"row1\" align=\"center\"><input type=\"text\" name=\"poz[$id]\" value=\"$weight\" maxlength=\"2\" style=\"text-align: center; width: 30px\"></td>\n";
		echo "<td class=\"row3\" align=\"center\">".ext_time($time, 2)."</td>\n";
		echo "<td class=\"row1\" align=\"center\">$hits</td>\n";
		echo "<td class=\"row3\" align=\"center\"><font color=\"red\">$active</font></td>\n";
		echo "<td class=\"row1\" align=\"center\" width=\"30\"><a href=\"?f=$adm_modname&do=edit_scroll&id=$id\" info=\""._EDIT."\"><img border=\"0\" src=\"images/edit.png\"></a></td>\n";
		echo "<td class=\"row3\" align=\"center\" width=\"30\"><a href=\"?f=$adm_modname&do=delete_scroll&id=$id\" info=\""._DELETE."\" onclick=\"return confirm('"._DELETEASK."');\"><img border=\"0\" src=\"images/delete.png\"></td>\n";
		echo "</tr>\n";
		$i ++;
	}
	if($total > $perpage) {
		echo "<tr><td colspan=\"6\">";
		$pageurl = "modules.php?f=".$adm_modname."&do=scroll";
		echo paging($total,$pageurl,$perpage,$page);
		echo "</td></tr>";
	}
	echo "<input type=\"hidden\" name=\"do\" value=\"quick_do_scroll\">";
	echo "<tr><td colspan=\"9\"><select name=\"fc\">";
	echo "<option value=\"\">&raquo; "._QUICKDO."</option>";
	echo "<option value=\"1\">&raquo; "._QUICKDO_1."</option>";
	echo "<option value=\"2\">&raquo; "._QUICKDO_2."</option>";
	echo "<option value=\"3\">&raquo; "._QUICKDO_3."</option>";
	echo "<option value=\"4\">&raquo; "._QUICKDO_4."</option>";
	echo "</select> <input type=\"submit\" name=\"submit\" value=\""._DOACTION."\"></td></tr>";
} else {
	echo "<tr><td colspan=\"9\"><center>"._NORECRUIT."</center></td></tr>";
}
echo "</table></form>";
echo "</div>";

include_once("page_footer.php");
?>