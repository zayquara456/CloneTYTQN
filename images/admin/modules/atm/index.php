<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}
$cityid = intval($_GET['id']);
$err_title = $title = $err_district = $address = $err_address = "";
$atm = 0;
if(isset($_POST['subup']) && $_POST['subup'] == 1) {
	$title = nospatags($_POST['title']);
	$districtid = intval($_POST['districtid']);
	$address = nospatags($_POST['address']);
	$atm = intval($_POST['atm']);
	
	if($title =="") {
		$err_title = "<font color=\"red\">"._ERROR1."</font><br>";
		$err = 1;
	}
	
	if($db->sql_numrows($db->sql_query("SELECT id FROM ".$prefix."_district WHERE cityid = '$cityid' AND alanguage='$currentlang'")) > 0) {
		if ($districtid == 0) {
			$err_district = "<font color=\"red\">"._ERROR2."</font><br>";
			$err = 1;
		}
	}	
	
	if($address =="") {
		$err_address = "<font color=\"red\">"._ERROR3."</font><br>";
		$err = 1;
	}

	if(!$err) {
		list($weightx) = $db->sql_fetchrow($db->sql_query("SELECT MAX(weight) AS weight FROM ".$prefix."_atm"));
		if($weightx == -1) { $weight = 1; } else { $weight = $weightx+1; }
		$db->sql_query("INSERT INTO ".$prefix."_atm (id, title, address, cityid, districtid, weight, atm, alanguage) VALUES (NULL, '$title', '$address', '$cityid', '$districtid', '$weight', '$atm', '$currentlang')");
		fixweight_atm();
		updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _ADD);
		header("Location: modules.php?f=".$adm_modname."&id=$cityid");
	}	
}	

if(isset($_POST['fixorder']) && $_POST['fixorder'] !="") {
	$poz = $_POST['poz'];
	foreach ($poz as $idx => $weightxx) {
		$db->sql_query("UPDATE ".$prefix."_atm SET weight='$weightxx' WHERE id='$idx'");
	}
	fixweight_atm();
	updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _FIXWEIGHT);
	header("Location: modules.php?f=".$adm_modname."&id=$cityid");
}	

include("page_header.php");
echo "<br><table border=\"0\" align=\"center\" cellspacing=\"0\" width=\"80%\" cellpadding=\"0\"><tr><td>\n";
echo "<form method=\"POST\" onsubmit=\"if(this.title.value=='') { this.title.focus(); return false; }\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
echo "<tr><td class=\"header\" colspan=\"2\">"._ADD_ATM."</td></tr>";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._TITLE."</b></td>\n";
echo "<td class=\"row3\">$err_title<input type=\"text\" name=\"title\" value=\"$title\" size=\"66\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._ATM_NAME."</b></td>\n";
if($atm == 1) {
	echo "<td  class=\"row2\"><input type=\"radio\" name=\"atm\" value=\"1\" checked>"._CHI_NHANH_1." &nbsp;&nbsp;"
	."<input type=\"radio\" name=\"atm\" value=\"0\">"._ATM."</td>\n";
} else {
	echo "<td  class=\"row2\"><input type=\"radio\" name=\"atm\" value=\"1\">"._CHI_NHANH_1." &nbsp;&nbsp;"
	."<input type=\"radio\" name=\"atm\" value=\"0\" checked>"._ATM."</td>\n";
}
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._DISTRICT."</b></td>\n";
$result_district = $db->sql_query("SELECT id, title FROM ".$prefix."_district WHERE alanguage='$currentlang' AND cityid = '$cityid' ORDER BY weight");
echo "		<td class=\"row3\">$err_district<select name=\"districtid\">";
echo "<option name=\"districtid\" value=\"0\">"._DISTRICT."</option>";
while(list($id, $title) = $db->sql_fetchrow($result_district)) {	
	echo "<option name=\"districtid\" value=\"$id\" >$title</option>";
}
echo "</select></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._ADDRESS."</b></td>\n";
echo "<td class=\"row3\">$err_address<textarea type=\"text\" name=\"address\" cols=\"50\" rows=\"3\">$address</textarea></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td class=\"row3\" colspan=\"2\" align=\"center\"><input type=\"hidden\" name=\"subup\" value=\"1\"><input type=\"submit\" name=\"submit\" value=\""._ADD."\" class=\"input1\"></td>\n";
echo "</tr>\n";
echo "</table></form><br>";

$perpage = 30;
$page = isset($_GET['page']) ? intval($_GET['page']) : (isset($_POST['page']) ? intval($_POST['page']) : 1);
$offset = ($page-1) * $perpage;
$total = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_atm WHERE alanguage='$currentlang' AND cityid=$cityid "));
$result = $db->sql_query("SELECT  id, title, address, weight, districtid, atm  FROM ".$prefix."_atm  WHERE alanguage='$currentlang' AND cityid='$cityid' ORDER BY weight ASC LIMIT $offset, $perpage");
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
echo "<form name=\"frm\" method=\"POST\" onsubmit=\"return checkQuick(this);\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
echo "<tr>\n";
echo "<tr><td colspan=\"8\" class=\"header\">"._MODTITLE."</td></tr>";
echo "<td class=\"row1sd\" width=\"50\" align=\"center\">"._POSITION."</td>\n";
echo "<td class=\"row1sd\" width=\"30%\">"._ATM_NAME."</td>\n";
echo "<td class=\"row1sd\" align=\"center\">"._ADDRESS."</td>\n";
echo "<td class=\"row1sd\" align=\"center\"  width=\"10%\">"._DISTRICT."</td>\n";
echo "<td class=\"row1sd\" align=\"center\"  width=\"50\">"._ATM."</td>\n";
echo "<td class=\"row1sd\" align=\"center\" width=\"30\">"._EDIT."</td>\n";
echo "<td class=\"row1sd\" align=\"center\" width=\"30\">"._DELETE."</td>\n";
echo "</tr>\n";
$cur_ar = array(_VND,_USD);
$i =0;
while(list($id, $title, $address, $weight,$districtid, $atm) = $db->sql_fetchrow($result)) {
if($i%2 == 1) { $css = "row1"; }	else { $css ="row3"; }
echo "<tr>\n";
echo "<td class=\"$css\" align=\"center\"><input type=\"text\" name=\"poz[$id]\" value=\"$weight\" size=\"2\"  style=\"text-align:center;color:red;\"></td>\n";
//echo "<td class=\"$css\"><a href=\"?f=district&do=edit&id=$id\" info=\""._EDIT."\"><b>$title</b></a></td>\n";
if($ajax_active == 1) {
	echo "<td class=\"row1\" id=\"".$adm_modname."_title_edit_".$mid."\"><a href=\"modules.php?f=$adm_modname&do=edit&id=$id\" title=\""._QUICK_EDIT."\" onclick=\"return show_edit_title($id,'$title','$adm_modname',30,'"._SAVECHANGES."','');\"><b>$title</b></a></td>\n";
} else {
	echo "<td class=\"row1\"><b>$title</b></td>\n";
}
echo "<td class=\"$css\">$address</td>\n";
$district = $db->sql_query("SELECT title FROM {$prefix}_district WHERE id = $districtid");
list($dist_title) = $db->sql_fetchrow();
echo "<td class=\"$css\" align=\"center\"><b>$dist_title</b></td>\n";

if ($atm == 1){echo "<td class=\"$css\" align=\"center\"><font color=\"red\">"._CHI_NHANH_1."</font></td>\n";}
else{echo "<td class=\"$css\" align=\"center\">"._ATM."</td>\n";}


echo "<td class=\"$css\" align=\"center\" width=\"30\"><a href=\"?f=".$adm_modname."&do=edit&id=$id&cid=$cityid\" info=\""._EDIT."\"><img border=\"0\" src=\"../images/edit.gif\"></a></td>\n";
echo "<td class=\"$css\" align=\"center\" width=\"30\"><a href=\"?f=".$adm_modname."&do=delete&id=$id\" info=\""._DELETE."\" onclick=\"return confirm('"._DELETEASK."');\"><img border=\"0\" src=\"images/delete.png\"></td>\n";
echo "</tr>\n";
$i ++;	
}
if($total > $perpage) {
	echo "<tr><td colspan=\"4\">";	
	$pageurl = "".$adm_modname.".php";
	echo paging($total,$pageurl,$perpage,$page,"",1);
	echo "</td></tr>";
}
echo "<tr><td colspan=\"7\" align=\"center\" class=\"row1\"><input type=\"submit\" name=\"fixorder\" value=\""._SAVEORDER."\"></td></tr>\n";
echo "</table></form><br>";	
}else{
	echo "<br>";
	OpenDiv();
	echo "<center>"._NODATA."</center>";
	CLoseDiv();
}		
echo "</td></tr></table>\n";
include("page_footer.php");

?>