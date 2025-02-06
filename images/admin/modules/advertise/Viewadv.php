<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$bnid = intval($_GET['id']);
$result_bn = $db->sql_query("SELECT title FROM ".$prefix."_advertise_banners WHERE bnid='$bnid'");
if(empty($bnid) || $db->sql_numrows($result_bn) != 1) {
	header("Location: modules.php?f=".$adm_modname."");
	exit;
}

list($titlebn) = $db->sql_fetchrow($result_bn);

include("page_header.php");

echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
echo "<tr>\n";
echo "<tr><td colspan=\"9\" class=\"header\">"._VIEWADVBN.": $titlebn</td></tr>";
$perpage = 20;
$page = isset($_GET['page']) ? intval($_GET['page']) : (isset($_GET['page']) ? intval($_POST['page']) : 1);
$offset = ($page-1) * $perpage;
$total = $db->sql_numrows($db->sql_query("SELECT*FROM ".$prefix."_advertise WHERE bnid='$bnid' AND alanguage='$currentlang'"));
$result = $db->sql_query("SELECT  id, title, time, active, hits, weight  FROM ".$prefix."_advertise WHERE bnid='$bnid' AND alanguage='$currentlang' ORDER BY weight LIMIT $offset, $perpage");
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
	echo "<form action=\"modules.php?f=$adm_modname&do=$do&id=$bnid&page=$page\" name=\"frm\" method=\"POST\" onsubmit=\"return checkQuick(this);\"><tr><td class=\"row3\" width=\"10\"><input type=\"checkbox\" name=\"checkall\" onclick=\"javascript:check_uncheck();\" title=\""._CHECKALL."\"></td>\n";
	echo "<td class=\"row1sd\">"._TITLE."</td>\n";
	echo "<td align=\"center\" width=\"50\" class=\"row3sd\">"._WEIGHT."</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"100\">"._DATESENT."</td>\n";
	echo "<td class=\"row3sd\" align=\"center\" width=\"80\">Clicks</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"80\">"._STATUS."</td>\n";
	echo "<td class=\"row3sd\" align=\"center\" width=\"30\">"._EDIT."</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"30\">"._DELETE."</td>\n";
	echo "</tr>\n";
	$cur_ar = array(_VND,_USD);
	$i =0;
	while(list($id, $title, $time, $active, $hits, $weight) = $db->sql_fetchrow($result)) {
		switch($active) {
			case 1: $active = "<a href=\"?f=$adm_modname&do=status&id=$id&stat=0\" info=\""._DEACTIVATE."\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
			case 0: $active = "<a href=\"?f=$adm_modname&do=status&id=$id&stat=1\" info=\""._ACTIVE."\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
		}

		echo "<tr>\n";
		echo "<td class=\"row1\"><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td>";
		echo "<td class=\"row3\"><a href=\"?f=$adm_modname&do=edit&id=$id\" info=\""._EDIT."\"><b>$title</b></a></td>\n";
		echo "<td class=\"row1\" align=\"center\"><input type=\"text\" name=\"poz[$id]\" value=\"$weight\" maxlength=\"2\" style=\"text-align: center; width: 30px\"></td>\n";
		echo "<td class=\"row3\" align=\"center\">".ext_time($time, 2)."</td>\n";
		echo "<td class=\"row1\" align=\"center\">$hits</td>\n";
		echo "<td class=\"row3\" align=\"center\"><font color=\"red\">$active</font></td>\n";
		echo "<td class=\"row1\" align=\"center\" width=\"30\"><a href=\"?f=$adm_modname&do=edit&id=$id\" info=\""._EDIT."\"><img border=\"0\" src=\"images/edit.png\"></a></td>\n";
		echo "<td class=\"row3\" align=\"center\" width=\"30\"><a href=\"?f=$adm_modname&do=delete&id=$id\" info=\""._DELETE."\" onclick=\"return confirm('"._DELETEASK."');\"><img border=\"0\" src=\"images/delete.png\"></td>\n";
		echo "</tr>\n";
		$i ++;
	}
	if($total > $perpage) {
		echo "<tr><td colspan=\"6\">";
		$pageurl = "modules.php?f=".$adm_modname.".php&do=viewadv&id=$bnid";
		echo paging($total,$pageurl,$perpage,$page);
		echo "</td></tr>";
	}
	echo "<input type=\"hidden\" name=\"do\" value=\"quick_do\">";
	echo "<input type=\"hidden\" name=\"bnid\" value=\"$bnid\">";
	echo "<tr><td colspan=\"9\"><select name=\"v\">";
	echo "<option value=\"\">&raquo; "._QUICKDO."</option>";
	echo "<option value=\"1\">&raquo; "._QUICKDO_1."</option>";
	echo "<option value=\"2\">&raquo; "._QUICKDO_2."</option>";
	echo "<option value=\"3\">&raquo; "._QUICKDO_3."</option>";
	echo "<option value=\"4\">&raquo; "._QUICKDO_4."</option>";
	echo "</select> <input type=\"submit\" name=\"submit\" value=\""._DOACTION."\"></td></tr>";
}else{
	echo "<tr><td colspan=\"9\"><center>"._NORECRUIT."</center></td></tr>";
}
echo "</table></form>";

include_once("page_footer.php");
?>