<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

include_once("page_header.php");

$perpage = 20;
$page = isset($_GET['page']) ? intval($_GET['page']) : (isset($_POST['page']) ? intval($_POST['page']) : 1);
$offset = ($page-1) * $perpage;
$total = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_newsletter WHERE status='2'"));
$result = $db->sql_query("SELECT  id, email, html, time, newsletterid FROM ".$prefix."_newsletter WHERE status='2' ORDER BY time DESC LIMIT $offset, $perpage");
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
	echo "function checkQuick(f) {\n";
	echo "		var a = 1;\n";
	echo "		var len = f.elements.length;\n";
	echo "		for (var i=0; i < len; i ++) {\n";
	echo "			if (f.elements[i].checked == true) {\n";
	echo "				a = 0;\n";
	echo "			}	\n";
	echo "		}\n";
	echo "		if(a == 1) {\n";
	echo "			alert('"._PLCHOOSE."');\n";
	echo "			return false;\n";
	echo "		}\n";
	echo " 		if(f.submit) { f.submit.disabled = true; }\n";
	echo "		return true;		\n";
	echo "	}\n";
	echo "</script>\n";
	ajaxload_content();

	echo "<div id=\"{$adm_modname}_main\"><form action=\"modules.php?f=$adm_modname&page=$page\" name=\"frm\" method=\"POST\" onsubmit=\"return checkQuick(this);\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" class=\"tableborder\">\n";
	echo "<tr>\n";
	echo "<tr><td colspan=\"8\" class=\"header\">"._MODTITLE."</td></tr>";
	echo "<td class=\"row1sd\" width=\"10\"><input type=\"checkbox\" name=\"checkall\" onclick=\"javascript:check_uncheck();\"></td>\n";
	echo "<td class=\"row3sd\">Email</td>\n";
	echo "<td class=\"row3sd\" align=\"center\" width=\"100\">"._CREATEDATE."</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"80\">"._NTYPE."</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"80\">"._RECEIVED."</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"30\">"._EDIT."</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"30\">"._DELETE."</td>\n";
	echo "</tr>\n";
	$i =0;
	while(list($id, $email, $ntype, $time, $newsletterid) = $db->sql_fetchrow($result)) {
		if($i%2 == 1) { $css = "row1"; } else { $css ="row3"; }
		if($newsletterid != '0') {
			$newsletterid_arr = @explode(",",$newsletterid);
			$numsreceived = "<a href=\"?f=$adm_modname&do=view_user&id=$id&page=1\" info=\""._DETAIL."\">".(sizeof($newsletterid_arr) - 1)." "._NEWSLETTERS."</a>";
		} else {
			$numsreceived = "0 "._NEWSLETTERS;
		}
		switch($ntype) {
			case 1: $ntype = "HTML"; break;
			case 0: $ntype = "Text"; break;
		}
		echo "<tr>\n";
		echo "<td class=\"$css\"><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td>";
		echo "<td class=\"$css\"><a href=\"mailto:$email\"><b>$email</b></a></td>\n";
		echo "<td align=\"center\" class=\"$css\">".ext_time($time, 2)."</td>\n";
		echo "<td align=\"center\" class=\"$css\">$ntype</td>\n";
		echo "<td align=\"center\" class=\"$css\">$numsreceived</td>\n";
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
	echo "<tr><td colspan=\"8\" class=\"row3\"><input type=\"submit\" name=\"submit\" value=\""._DELCHOOSED."\"> <input type=\"button\" value=\""._ADDEMAIL."\" onclick=\"window.location='?f=$adm_modname&do=add'\"></td></tr>";
	echo "</form></table></div><br/>";
}else{
	OpenDiv();
	echo "<center>"._NODATA."<br/><br/><input type=\"button\" value=\""._ADDEMAIL."\" onclick=\"window.location='?f=$adm_modname&do=add'\"></center>";
	CLoseDiv();
}

include_once("page_footer.php");

?>