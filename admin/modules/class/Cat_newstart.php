<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$catid = intval(isset($_GET['id']) ? $_GET['id'] : $_POST['id']);
//$sid = intval(isset($_GET['sid']) ? $_GET['sid'] : (isset($_POST['sid']) ? $_POST['sid']:0));
$action = isset($_GET['sort']) ? $_GET['sort'] : (isset($_POST['sort']) ? $_POST['sort']:"");
$sort = intval(isset($_GET['sort']) ? $_GET['sort'] : (isset($_POST['sort']) ? $_POST['sort']:0));
$result_cat = $db->sql_query("SELECT title, startid FROM ".$prefix."_document_cat WHERE catid='$catid'");
if(empty($catid) || $db->sql_numrows($result_cat) != 1) {
	header("Location: ".$adm_modname.".php?do=categories");
	exit;
}	

if($action == "choose") {
	$db->sql_query("UPDATE ".$prefix."_document_cat SET startid='$sid' WHERE catid='$catid'");
	header("Location: ".$adm_modname.".php?do=categories");
	exit;
}	

list($catname, $startid) = $db->sql_fetchrow($result_cat);	

include("page_header.php");
	switch($sort) {
		default: $sortby ="ORDER BY time DESC"; break;
		case 1: $sortby ="ORDER BY id ASC"; break;
		case 2: $sortby ="ORDER BY id DESC"; break;
		case 3: $sortby ="ORDER BY time ASC"; break;
		case 4: $sortby ="ORDER BY time DESC"; break;
		case 5: $sortby ="ORDER BY hits ASC"; break;
		case 6: $sortby ="ORDER BY hits DESC"; break;
	}	
$perpage = 10;
$page = intval(isset($_GET['page']) ? $_GET['page'] : (isset($_POST['page']) ? $_POST['page']:1));
if ($page == 0) { $page = 1; }
$offset = ($page-1) * $perpage;
$total = $db->sql_numrows($db->sql_query("SELECT*FROM ".$prefix."_document WHERE catid='$catid' AND alanguage='$currentlang'"));
$result = $db->sql_query("SELECT id, title, time, active, hits FROM ".$prefix."_document WHERE catid='$catid' AND alanguage='$currentlang' $sortby LIMIT $offset, $perpage");
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
echo "<form name=\"frm\" action=\"".$adm_modname.".php\" method=\"POST\" onsubmit=\"return checkQuick(this);\">";
echo "<table border=\"0\" width=\"100%\" cellspacing=\"1\" cellpadding=\"4\" class=\"tableborder\">\n";
echo "<tr><td colspan=\"8\" class=\"header\">"._NEWSTART1." \"<font color=\"#FFFF00\">$catname</font>\"</td></tr>";
echo "<tr>\n";
echo "<td class=\"row3\" width=\"20\" align=\"center\"><a href=\"?do=cat_newstart&catid=$catid&sort=1\" info=\""._SORTUP."\"><img border=\"0\" src=\"images/sup.gif\"></a> <a href=\"?do=cat_newstart&catid=$catid&sort=2\" info=\""._SORTDOWN."\"><img border=\"0\" src=\"images/sdown.gif\"></a></td>\n";
echo "<td class=\"row3\" width=\"10\"><input type=\"checkbox\" name=\"checkall\" onclick=\"javascript:check_uncheck();\"></td>\n";
echo "<td class=\"row3\">"._TITLE."</td>\n";
echo "<td class=\"row3\" align=\"center\" width=\"100\">"._TIMEUP." <a href=\"?do=cat_newstart&catid=$catid&sort=3\" info=\""._SORTUP."\"><img border=\"0\" src=\"images/sup.gif\" align=\"absmiddle\"></a> <a href=\"?do=cat_newstart&catid=$catid&sort=4\" info=\""._SORTDOWN."\"><img border=\"0\" src=\"images/sdown.gif\" align=\"absmiddle\"></a></td>\n";
echo "<td class=\"row3\" align=\"center\" width=\"60\">"._NEWSTART."</td>\n";
echo "<td class=\"row3\" align=\"center\" width=\"80\">"._VIEW." <a href=\"?do=cat_newstart&catid=$catid&sort=5\" info=\""._SORTUP."\"><img border=\"0\" src=\"images/sup.gif\" align=\"absmiddle\"></a> <a href=\"?do=cat_newstart&catid=$catid&sort=6\" info=\""._SORTDOWN."\"><img border=\"0\" src=\"images/sdown.gif\" align=\"absmiddle\"></a></td>\n";
echo "<td class=\"row3\" align=\"center\" width=\"30\">"._EDIT."</td>\n";
echo "<td class=\"row3\" align=\"center\" width=\"30\">"._DELETE."</td>\n";
echo "</tr>\n";
$i =0;
$a = 1;
if($page > 1) { $a = $perpage*$page - $perpage + 1;}
while(list($id, $title, $time, $active, $hits) = $db->sql_fetchrow($result)) {
if($i%2 == 1) { $bgcolor = "#F7F7F7"; }	else { $bgcolor ="#FFFFFF"; }	

if($id == $startid) {
	$bgcolor = "#FBE0C1";
	$active = "<a href=\"modules.php?f=".$adm_modname."&do=cat_newstart&catid=$catid&action=choose&sid=0\" info=\""._NOCHOOSETONEWSTART."\"><img border=\"0\" src=\"../images/active.gif\"></a>"; 
} else {	
	$active = "<a href=\"modules.php?f=".$adm_modname."&do=cat_newstart&catid=$catid&action=choose&sid=$id\" info=\""._CHOOSETONEWSTART."\"><img border=\"0\" src=\"../images/deactive.gif\"></a>";
}	

echo "<tr bgcolor=\"$bgcolor\">\n";
echo "<td align=\"center\">$a</td>";
echo "<td><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td>";
echo "<td><b><a href=\"../news.php?do=detail&id=$id\">$title</a></b></td>\n";
echo "<td align=\"center\">".ext_time($time, 2)."</td>\n";
echo "<td align=\"center\">$active</td>\n";
echo "<td align=\"center\">$hits</td>\n";
echo "<td align=\"center\" width=\"30\"><a href=\"?do=edit_news&id=$id\" info=\""._EDIT."\"><img border=\"0\" src=\"../images/edit.gif\"></a></td>\n";
echo "<td align=\"center\" width=\"30\"><a href=\"?do=delete_news&id=$id\" info=\""._DELETE."\" onclick=\"return confirm('"._DELETEASK1."');\"><img border=\"0\" src=\"../images/trash.gif\"></td>\n";
echo "</tr>\n";
$i ++;	
$a ++;
}
if($total > $perpage) {
	echo "<tr><td colspan=\"6\">";	
	$pageurl = "".$adm_modname.".php?do=cat_newstart&catid=$catid";
	echo paging($total,$pageurl,$perpage,$page);
	echo "</td></tr>";
}	
echo "<input type=\"hidden\" name=\"do\" value=\"quick_do\">";
echo "<tr><td colspan=\"8\"><select name=\"f\">";
echo "<option value=\"\">&raquo; "._QUICKDO."</option>";
echo "<option value=\"1\">&raquo; "._QUICKDO_1."</option>";
echo "<option value=\"2\">&raquo; "._QUICKDO_2."</option>";
echo "<option value=\"3\">&raquo; "._QUICKDO_3."</option>";
echo "</select> <input type=\"submit\" name=\"submit\" value=\""._DOACTION."\"> <input type=\"button\" value=\""._NOCHOOSESTART."\" onclick=\"window.location='".$adm_modname.".php?do=cat_newstart&catid=$catid&action=choose&sid=0'\"></td></tr>";
echo "</table></form><br>";	
} else {
	header("Location: ".$adm_modname.".php?do=categories");
	exit;
}	

include("page_footer.php");

?>