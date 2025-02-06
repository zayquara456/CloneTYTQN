<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

include("page_header.php");

$perpage = 20;
$page = isset($_GET['page']) ? intval($_GET['page']) : (isset($_POST['page']) ? intval($_POST['page']) : 1);
$offset = ($page-1) * $perpage;
$total = $db->sql_numrows($db->sql_query("SELECT*FROM ".$prefix."_survey WHERE alanguage='$currentlang'"));
$result = $db->sql_query("SELECT  id, question, anwsers, time, active, hits  FROM ".$prefix."_survey WHERE alanguage='$currentlang' ORDER BY time DESC LIMIT $offset, $perpage");
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
ajaxload_content();

echo "<div id=\"{$adm_modname}_main\"><form action=\"modules.php?f=$adm_modname&do=$do&page=$page\" name=\"frm\" method=\"POST\" onsubmit=\"return checkQuick(this);\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" class=\"tableborder\">\n";
echo "<tr>\n";
echo "<tr><td colspan=\"8\" class=\"header\">"._MODTITLE."</td></tr>";
echo "<td class=\"row1sd\" width=\"10\"><input type=\"checkbox\" name=\"checkall\" onclick=\"javascript:check_uncheck();\"></td>\n";
echo "<td class=\"row3sd\">"._TITLE."</td>\n";
echo "<td class=\"row3sd\" align=\"center\" width=\"100\">"._CREATEDATE."</td>\n";
echo "<td class=\"row1sd\" align=\"center\" width=\"80\">"._ANWSER."</td>\n";
echo "<td class=\"row1sd\" align=\"center\" width=\"80\">"._STATUS."</td>\n";
echo "<td class=\"row3sd\" align=\"center\" width=\"30\">"._VIEW."</td>\n";
echo "<td class=\"row1sd\" align=\"center\" width=\"30\">"._EDIT."</td>\n";
echo "<td class=\"row1sd\" align=\"center\" width=\"30\">"._DELETE."</td>\n";
echo "</tr>\n";
$i =0;
while(list($id, $question, $anwsers, $time, $active, $hits) = $db->sql_fetchrow($result)) {
	if($i%2 == 1) { $css = "row1"; } else { $css ="row3"; }	
	$anwsers_arr = @explode("|",$anwsers);
	if($ajax_active == 1) {
		switch($active) {
			case 1: $active = "<a href=\"?f=$adm_modname&do=status&id=$id&stat=0\" title=\""._DEACTIVATE."\" onclick=\"return aj_base_status($id,0,'$adm_modname','','');\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
			case 0: $active = "<a href=\"?f=$adm_modname&do=status&id=$id&stat=1\" title=\""._ACTIVE."\" onclick=\"return aj_base_status($id,1,'$adm_modname','','');\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
		}	
	} else {
		switch($active) {
			case 1: $active = "<a href=\"?f=$adm_modname&do=status&id=$id&stat=0\" info=\""._DEACTIVATE."\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
			case 0: $active = "<a href=\"?f=$adm_modname&do=status&id=$id&stat=1\" info=\""._ACTIVE."\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
		}
	}	
	echo "<tr>\n";
	echo "<td class=\"$css\"><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td>";
	if($ajax_active == 1) {
		echo "<td class=\"$css\" id=\"".$adm_modname."_title_edit_".$id."\"><a href=\"modules.php?f=$adm_modname&do=edit&id=$id\" title=\""._QUICK_EDIT."\" onclick=\"return show_edit_title($id,'$question','$adm_modname',40,'"._SAVECHANGES."','');\"><b>$question</b></a></td>\n";
	} else {
		echo "<td class=\"$css\"><b>$question</b></td>\n";
	}	
	echo "<td align=\"center\" class=\"$css\">".ext_time($time, 2)."</td>\n";
	echo "<td align=\"center\" class=\"$css\">".sizeof($anwsers_arr)."</td>\n";
	echo "<td align=\"center\" class=\"$css\"><font color=\"red\">$active</font></td>\n";
	echo "<td align=\"center\" width=\"30\" class=\"$css\">$hits</td>\n";
	echo "<td align=\"center\" width=\"30\" class=\"$css\"><a href=\"?f=$adm_modname&do=edit&id=$id\" info=\""._EDIT."\"><img border=\"0\" src=\"images/edit.png\"></a></td>\n";
	if($ajax_active == 1) {
		echo "<td align=\"center\" width=\"30\" class=\"$css\"><a href=\"?f=$adm_modname&do=delete&id=$id\" title=\""._DELETE."\" onclick=\"return aj_base_delete($id,'$adm_modname','"._DELETEASK."','','');\"><img border=\"0\" src=\"images/delete.png\"></td>\n";
	} else {
		echo "<td align=\"center\" width=\"30\" class=\"$css\"><a href=\"?f=$adm_modname&do=delete&id=$id\" info=\""._DELETE."\" onclick=\"return confirm('"._DELETEASK."');\"><img border=\"0\" src=\"images/delete.png\"></td>\n";
	}	
	echo "</tr>\n";
	$i ++;	
}
if($total > $perpage) {
	echo "<tr><td colspan=\"8\">";	
	$pageurl = "".$adm_modname.".php";
	echo paging($total,$pageurl,$perpage,$page);
	echo "</td></tr>";
}	
echo "<input type=\"hidden\" name=\"do\" value=\"quick_do\">";
echo "<tr><td colspan=\"8\" class=\"row3\"><select name=\"v\">";
echo "<option value=\"\">&raquo; "._QUICKDO."</option>";
echo "<option value=\"1\">&raquo; "._QUICKDO_1."</option>";
echo "<option value=\"2\">&raquo; "._QUICKDO_2."</option>";
echo "<option value=\"3\">&raquo; "._QUICKDO_3."</option>";
echo "</select> <input type=\"submit\" name=\"submit\" value=\""._DOACTION."\"></td></tr>";
echo "</table></form></div><br/>";
	
}else{
	OpenDiv();
	echo "<center>"._NODATA."</center>";
	CLoseDiv();
}		

include_once("page_footer.php");
?>