<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$id = isset($_GET['id']) ? $_GET['id'] : $_POST['id'];
$result = $db->sql_query("SELECT email, newsletterid FROM ".$prefix."_newsletter WHERE id=$id");
if (empty($id) || $db->sql_numrows($result) != 1) die();

list($email, $newsletterid) = $db->sql_fetchrow($result);

if ($newsletterid != "0") {

	$newsletterid_arr = @explode(",",$newsletterid);
	$sqlseld_arr = "";
	for($i = 1; $i < sizeof($newsletterid_arr); $i ++) {
		$sqlseld_arr[] = "id='$newsletterid_arr[$i]'";
	}
	$sqlseld = @implode(" OR ",$sqlseld_arr);
	
	include("page_header.php");
	
	$perpage = 20;
	$page = isset($_GET['page']) ? intval($_GET['page']) : intval($_POST['page']);
	if ($page == 0) { $page = 1; }
	$offset = ($page-1) * $perpage;
	$total = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_newsletter_send WHERE ($sqlseld)"));
	$result = $db->sql_query("SELECT  id, subject, send FROM ".$prefix."_newsletter_send WHERE ($sqlseld) ORDER BY send DESC LIMIT $offset, $perpage");
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
		echo "			alert('"._PLCHOOSE1."');\n";
		echo "			return false;\n";
		echo "		}\n";
		echo " 		if(f.submit) { f.submit.disabled = true; }\n";
		echo "		return true;		\n";
		echo "	}\n";
		echo "</script>\n";
		echo "<br/><form action=\"modules.php?f=$adm_modname&do=$do&id=$id&page=$page\" name=\"frm\" method=\"POST\" onsubmit=\"return checkQuick(this);\"><table align=\"center\" border=\"0\" width=\"\" cellspacing=\"0\" cellpadding=\"3\" class=\"tableborder\">\n";
		echo "<tr>\n";
		echo "<tr><td colspan=\"8\" class=\"header\">"._MODTITLE." &raquo; "._SENTNLT1." $email</td></tr>";
		echo "<td class=\"row1sd\" width=\"10\"><input type=\"checkbox\" name=\"checkall\" onclick=\"javascript:check_uncheck();\"></td>\n";
		echo "<td class=\"row3sd\">"._TITLE."</td>\n";
		echo "<td class=\"row3sd\" align=\"center\" width=\"100\">"._SENTDATE."</td>\n";
		echo "<td class=\"row1sd\" align=\"center\" width=\"30\">"._DELETE."</td>\n";
		echo "</tr>\n";
		$i =0;
		while(list($id, $title, $time) = $db->sql_fetchrow($result)) {
			if($i%2 == 1) { $css = "row1"; } else { $css ="row3"; }
			echo "<tr>\n";
			echo "<td class=\"$css\"><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td>";
			echo "<td class=\"$css\"><a href=\"?f=newsletter&do=view_sent&id=$id\" info=\""._VIEW."\"><b>$title</b></a></td>\n";
			echo "<td align=\"center\" class=\"$css\">".ext_time($time, 2)."</td>\n";
			echo "<td align=\"center\" width=\"30\" class=\"$css\"><a href=\"?do=deletesent&id=$id\" info=\""._DELETE."\" onclick=\"return confirm('"._DELETEASK1."');\"><img border=\"0\" src=\"images/delete.png\"></td>\n";
			echo "</tr>\n";
			$i ++;
		}
		if($total > $perpage) {
			echo "<tr><td colspan=\"8\">";
			$pageurl = "{$adm_modname}.php?do=sent";
			echo paging($total,$pageurl,$perpage,$page);
			echo "</td></tr>";
		}
		echo "<input type=\"hidden\" name=\"do\" value=\"quick_do\">";
		echo "<tr><td colspan=\"8\" class=\"row3\" align=\"center\"><input type=\"submit\" name=\"submit\" value=\""._DELCHOOSEDNS."\"></td></tr>";
		echo "</form></table><br/>";

	}
} else {
	header("Location: modules.php?f=$adm_modname");
}

include_once("page_footer.php");

?>