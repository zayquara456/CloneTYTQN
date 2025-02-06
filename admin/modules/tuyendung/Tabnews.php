<?php

if(!defined('CMS_ADMIN')) die("Illegal File Access");

include_once("page_header.php");
$menu_type=$where="";
$page = isset($_GET['page']) ? intval($_GET['page']) : (isset($_POST['page']) ? intval($_POST['page']) :1);
$newsid =isset($_GET['newsid'])? intval($_GET['newsid']):0;
$type =isset($_GET['type'])? $_GET['type']:"";


if($newsid!=0)
	$where="newsid='$newsid' AND";
else
	header("Location: modules.php?f=".$adm_modname."");
if(isset($_POST['subup']) && $_POST['subup'] == 1) {
	$title = trim(stripslashes(resString($_POST['title'])));
	$content = $escape_mysql_string(trim($_POST['content']));
	$newsid= $newsid;
	if($title =="") {
		$err_title = "<font color=\"red\">"._ERROR_TAB1."</font><br/>";
		$err = 1;
	}	
	
	//if($content =="") {
	//	$err_content = "<font color=\"red\">"._ERROR_TAB2."</font><br/>";
	//	$err = 1;
	//}	
	
	if(!$err) {
		$weight = WeightMax("{$adm_modname}_tab");
		$db->sql_query("INSERT INTO ".$prefix."_{$adm_modname}_tab (tabid, newsid, title, content, alanguage, weight) VALUES (NULL, '$newsid', '$title', '$content', '$currentlang', '$weight')");
		fixweight_newstab();
		header("Location: modules.php?f=".$adm_modname."&do=tabnews&newsid=$newsid");
	}	
}
else {
	//$err_title = "";
	//$err_url = "";
	//$title  = "";
	//$url  = "";
}

ajaxload_content();

echo "<script language=\"javascript\">\n";
echo "function check(f) {\n";
echo "if(f.title.value =='') {\n";
echo "alert('"._ERROR_TAB1."');\n";
echo "f.title.focus();\n";
echo "return false;\n";
echo "}\n";
/*echo "if(f.content.value =='') {\n";
echo "alert('"._ERROR_TAB2."');\n";
echo "f.content.focus();\n";
echo "return false;\n";
echo "}\n";*/
echo "f.submit.disabled = true;\n";
echo "return true;	\n";
echo "}	\n";
echo "</script>	\n";


echo "<div id=\"".$adm_modname."_main\"><form action=\"modules.php?f=$adm_modname&do=tabnews&newsid=$newsid\" method=\"POST\" onsubmit=\"return check(this);\" enctype=\"multipart/form-data\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
echo "<tr><td colspan=\"2\" class=\"header\">"._CREATE_TABS."</td></tr>";
echo "<tr>\n";
echo "<td width=\"200px\" align=\"right\" class=\"row1\"><b>"._TITLE."</b></td>\n";
echo "<td class=\"row2\">$err_title<input type=\"text\" name=\"title\" value=\"$title\" size=\"40\"></td>\n";
echo "</tr>\n";
/*echo "<tr>\n";
echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>"._CONTENT_TAB."</b></td>\n";
echo "<td class=\"row2\">$err_url<input type=\"text\" name=\"content\" value=\"$content\" size=\"50\"></td>\n";
echo "</tr>\n";*/
echo "<tr>\n";
		echo "<td align=\"right\" class=\"row1\"><b>"._BODYTEXT."</b></td>\n";
		echo "<td class=\"row2\" colspan=\"2\">$err_content";
		editor("content", $content,"",400);
		echo "</td>\n";
		echo "</tr>\n";
echo "<input type=\"hidden\" name=\"subup\" value=\"1\">";
echo "<tr><td colspan=\"2\" align=\"center\" class=\"row1\"><input type=\"submit\" name=\"submit\" value=\""._ADD."\"></td></tr>";
echo "</table></form>";

//////////////////////////////////DANH SACH MENU//////////////////////////////////////////////////
$resultcat = $db->sql_query("SELECT tabid, title, active, weight, content FROM ".$prefix."_{$adm_modname}_tab WHERE $where alanguage='$currentlang' ORDER BY weight,tabid ASC");
if($db->sql_numrows($resultcat) > 0) {
echo "<script language=\"javascript\" type=\"text/javascript\">\n";
echo "function check_uncheck(){\n";
echo "var f= document.frm;\n";
echo "if(f.checkall.checked){\n";
echo "CheckAllCheckbox(f,'tabid[]');\n";
echo "}else{\n";
echo "UnCheckAllCheckbox(f,'tabid[]');\n";
echo "}			\n";
echo "}\n";
echo "function checkQuick(f) {\n";
echo "if(f.f.value =='') {\n";
echo "f.f.focus();\n";
echo "return false;\n";
echo "}\n";
echo "return true;		\n";
echo "}	\n";
echo "</script>\n";		
echo "<br/><form name=\"frm\" action=\"modules.php\" method=\"POST\" onsubmit=\"return checkQuick(this);\">";
echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
echo "<tr><td colspan=\"9\" class=\"header\">"._LIST_TABS."</td></tr>";
echo "<tr>\n";
echo "<td width=\"1%\" align=\"center\" class=\"row1sd\"><input type=\"checkbox\" name=\"checkall\" onclick=\"javascript:check_uncheck();\" title=\""._CHECKALL."\"></td>\n";
echo "<td class=\"row1sd\">"._TITLE."</td>\n";
echo "<td align=\"center\" width=\"50\" class=\"row1sd\">"._WEIGHT."</td>\n";
echo "<td align=\"center\" width=\"50\" class=\"row1sd\"><b>"._SHOW."</b></td>\n";
echo "<td align=\"center\" width=\"30\" class=\"row1sd\">"._EDIT."</td>\n";
echo "<td align=\"center\" width=\"30\" class=\"row1sd\">"._DELETE."</td>\n";
echo "</tr>\n";
while(list($tabid, $title, $active, $weight, $content) = $db->sql_fetchrow($resultcat)) {

switch($active) {
	case 1: $active = "<a href=\"?f=$adm_modname&do=tab_status&newsid=$newsid&tabid=$tabid&stat=0\" info=\""._DEACTIVATE."\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
	case 0: $active = "<a href=\"?f=$adm_modname&do=tab_status&newsid=$newsid&tabid=$tabid&stat=1\" info=\""._ACTIVE."\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
}	
switch($target) {
	case 1: $target = "<img border=\"0\" src=\"images/ticko.png\">"; break;	
	case 0: $target = "<img border=\"0\" src=\"images/tick.png\">"; break;	
}	
	
echo "<tr>\n";
echo "<td align=\"center\" class=\"row1\"><input type=\"checkbox\" name=\"tabid[]\" value=\"$tabid\"></td>\n";
echo "<td class=\"row1\"><b>$title</b></td>\n";
echo "<td align=\"center\" class=\"row1\"><input type=\"text\" name=\"poz[$tabid]\" value=\"$weight\" maxlength=\"2\" style=\"text-align: center; width: 30px; font-weight: bold\"></td>\n";
echo "<td align=\"center\" class=\"row1\">$active</td>\n";
echo "<td align=\"center\" class=\"row1\"><a href=\"?f=$adm_modname&do=tabnews&type=edit&newsid=$newsid&tabid=$tabid\" info=\""._EDIT."\"><img border=\"0\" src=\"images/edit.png\"></a></td>\n";
echo "<td align=\"center\" class=\"row1\"><a href=\"?f=$adm_modname&do=tabdelete&newsid=$newsid&tabid=$tabid\" info=\""._DELETE."\" onclick=\"return confirm('"._DELETEASK."');\"><img border=\"0\" src=\"images/delete.png\"></td>\n";
//echo childcat($mid);
}
echo "<input type=\"hidden\" name=\"f\" value=\"$adm_modname\">";
echo "<input type=\"hidden\" name=\"do\" value=\"quick\">";
/*if($total > $perpage) {
	echo "<tr><td colspan=\"9\">";	
	$pageurl = "modules.php?f=".$adm_modname."&newsid=$newsid";
	echo paging($total,$pageurl,$perpage,$page);
	echo "</td></tr>";
}	*/
echo "<tr><td colspan=\"8\"><select name=\"v\">";
echo "<option value=\"\">&raquo; "._QUICKDO."</option>";
echo "<option value=\"1\">&raquo; "._QUICKDO_2."</option>";
echo "<option value=\"2\">&raquo; "._QUICKDO_3."</option>";
echo "<option value=\"3\">&raquo; "._QUICKDO_4."</option>";
echo "<option value=\"4\">&raquo; "._QUICKDO_1."</option>";
echo "</select> <input type=\"submit\" value=\""._DOACTION."\"></td></tr>";
echo "</table></form></div><br/>";
}
include_once("page_footer.php");

?>