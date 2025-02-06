<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

include_once("page_header.php");

$sort = intval(isset($_GET['sort']) ? $_GET['sort'] : (isset($_POST['sort']) ? $_POST['sort']:0));
switch($sort) {
	case 1: $sortby ="ORDER BY catid ASC"; break;
	case 2: $sortby ="ORDER BY catid DESC"; break;
	case 3: $sortby ="ORDER BY time ASC"; break;
	case 4: $sortby ="ORDER BY time DESC"; break;
	case 5: $sortby ="ORDER BY hits ASC"; break;
	case 6: $sortby ="ORDER BY hits DESC"; break;
	default: $sortby ="ORDER BY time DESC"; break;
}
$perpage = 20;
$page = intval(isset($_GET['page']) ? $_GET['page'] : (isset($_POST['page']) ? $_POST['page']:1));
$offset = ($page-1) * $perpage;
$newsArr = array();
$newstitle = $db->sql_query("SELECT id, title FROM {$prefix}_news");
while (list($nid, $ntitle) = $db->sql_fetchrow()) $newsArr[$nid] = $ntitle;
$total = $db->sql_numrows($db->sql_query("SELECT id FROM {$prefix}_comments WHERE alanguage='$currentlang'"));
$result = $db->sql_query("SELECT id, newsid, title, content, name, email, time, status FROM {$prefix}_comments WHERE alanguage='$currentlang' $sortby LIMIT $offset, $perpage");
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
	echo "	function checkQuickId(f) {\n";
	echo "		if(f.id.value =='') {\n";
	echo "			f.id.focus();\n";
	echo "			return false;\n";
	echo "		}\n";
	echo "		f.submit.disabled = true; \n";
	echo "		return true;		\n";
	echo "	}	\n";
	echo "</script>\n";
	ajaxload_content();

	echo "<div id=\"{$adm_modname}_main\"><form action=\"modules.php?f=$adm_modname&sort=$sort&page=$page\" name=\"frm\" method=\"POST\" onsubmit=\"return checkQuick(this);\">";
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
	echo "<tr><td colspan=\"9\" class=\"header\">"._ALL_NEWS_COMMENTS."</td></tr>";
	echo "<tr>\n";
	echo "<td class=\"row1sd\" width=\"10\"><input type=\"checkbox\" name=\"checkall\" onclick=\"javascript:check_uncheck();\"></td>\n";
	echo "<td class=\"row1sd\" align=\"center\">"._COMMENT."</td>\n";
	echo "<td class=\"row1sd\">"._TITLE_NEWS_COMMENT."</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"60\">"._STATUS_COMMENT."</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"30\">"._DELETE_COMMENT."</td>\n";
	echo "</tr>\n";
	$i = 0;
	if($page > 1) { $a = $perpage * $page - $perpage + 1;}
	while(list($id, $newsid, $title, $content, $name, $email, $time, $status) = $db->sql_fetchrow($result)) {
		if (($i % 2) == 1) $css = "row1";
		else $css ="row3";
			switch($status) {
				case 1: $status = "<a href=\"?f=".$adm_modname."&do=status_comments&id=$id&stat=0\" info=\""._DEACTIVATE."\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
				case 0: $status = "<a href=\"?f=".$adm_modname."&do=status_comments&id=$id&stat=1\" info=\""._ACTIVE."\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
			}

		echo "<tr>\n";
		echo "<td class=\"$css\"><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td>";
		echo "<td class=\"$css\"><b>$name</b> - <i>".ext_time($time, 2)."</i><br>$content</td>\n";
		echo "<td class=\"$css\"><b><a href=\"".RPATH.url_sid("index.php?f=".$adm_modname."&do=detail&id=$newsid")."\" info=\""._VIEW."\" target=\"_blank\">{$newsArr[$newsid]}</a></b></td>\n";
		echo "<td align=\"center\" class=\"$css\">$status</td>\n";
		echo "<td align=\"center\" width=\"10\" class=\"$css\"><a href=\"?f=".$adm_modname."&do=delete_comments&id=$id\" info=\""._DELETE."\" onclick=\"return confirm('"._DELETEASK1."');\"><img border=\"0\" src=\"images/delete.png\"></td>\n";
		echo "</tr>\n";
		$i++;
	}
	if($total > $perpage) {
		echo "<tr><td colspan=\"9\">";
		$pageurl = "modules.php?f=".$adm_modname."&do=$do";
		echo paging($total,$pageurl,$perpage,$page);
		echo "</td></tr>";
	}
	echo "<input type=\"hidden\" name=\"do\" value=\"quick_do\">";
	echo "<tr><td colspan=\"9\" class=\"row3\"><select name=\"fc\">";
	echo "<option value=\"\">&raquo; "._QUICKDO."</option>";
	echo "<option value=\"1\">&raquo; "._QUICKDO_1."</option>";
	echo "<option value=\"2\">&raquo; "._QUICKDO_2."</option>";
	echo "<option value=\"3\">&raquo; "._QUICKDO_3."</option>";
	echo "</select>&nbsp;<input type=\"submit\" name=\"submit\" value=\""._DOACTION."\"></form></td></tr>";
	echo "</table><br /></div>";
} else {
	OpenDiv();
	echo "<center>"._NONEWSPOST."</center>";
	echo "<META HTTP-EQUIV=\"refresh\" content=\"5;URL=modules.php?f=".$adm_modname."&do=create\">";
	CLoseDiv();
}

include_once("page_footer.php");
?>