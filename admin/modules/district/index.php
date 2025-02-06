<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}
$cityid = intval($_GET['id']);
$err_title = $title = $err_cat ="";
if(isset($_POST['subup']) && $_POST['subup'] == 1) {
	$title = nospatags($_POST['title']);
	if($title =="") {
		$err_title = "<font color=\"red\">"._ERROR1."</font><br>";
		$err = 1;
	}
	
	if($db->sql_numrows($db->sql_query("SELECT title FROM ".$prefix."_district WHERE title='$title' AND alanguage='$currentlang'")) > 0) {
		$err_title = "<font color=\"red\">"._ERROR2."</font><br>";
		$err = 1;
	}	
	if ($cityid == 0) {
		$err_cat = "<font color=\"red\">"._ERROR1_1."</font><br>";
		$err = 1;
	}

	if(!$err) {
		list($weightx) = $db->sql_fetchrow($db->sql_query("SELECT MAX(weight) AS weight FROM ".$prefix."_district"));
		if($weightx == -1) { $weight = 1; } else { $weight = $weightx+1; }
		$db->sql_query("INSERT INTO ".$prefix."_district (id, title, weight, alanguage, cityid) VALUES (NULL, '$title', '$weight', '$currentlang', '$cityid')");
		fixweight_district();
		updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _ADD);
		header("Location: modules.php?f=".$adm_modname."&id=$cityid");
	}	
}	

if(isset($_POST['fixorder']) && $_POST['fixorder'] !="") {
	$poz = $_POST['poz'];
	foreach ($poz as $idx => $weightxx) {
		$db->sql_query("UPDATE ".$prefix."_district SET weight='$weightxx' WHERE id='$idx'");
	}
	fixweight_district();
	updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _FIXWEIGHT);
	header("Location: modules.php?f=".$adm_modname."&id=$cityid");
}	

include("page_header.php");
echo "<br><table border=\"0\" align=\"center\" cellspacing=\"0\" width=\"80%\" cellpadding=\"0\"><tr><td>\n";
echo "<form method=\"POST\" onsubmit=\"if(this.title.value=='') { this.title.focus(); return false; }\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
echo "<tr><td class=\"header\" colspan=\"2\">"._ADDDISTRICT."</td></tr>";
/*
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._CITY."</b></td>\n";
$result_cat = $db->sql_query("SELECT id, title FROM ".$prefix."_city WHERE alanguage='$currentlang' ORDER BY weight");
echo "		<td class=\"row2\">$err_cat<select name=\"cityid\">";
echo "<option name=\"cityid\" value=\"0\">"._CITY."</option>";
while(list($id, $title) = $db->sql_fetchrow($result_cat)) {
	echo "<option value=\"$id\" style=\"font-weight: bold\">$title</option>";
}
echo "</select></td>\n";
echo "</tr>\n";
*/
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._DISTRICTNAME."</b></td>\n";
echo "<td class=\"row3\">$err_title<input type=\"text\" name=\"title\" value=\"$title\" size=\"30\"> <input type=\"hidden\" name=\"subup\" value=\"1\"><input type=\"submit\" name=\"submit\" value=\""._ADD."\" class=\"input1\"></td>\n";
echo "</tr>\n";
echo "</table></form><br>";

$perpage = 30;
$page = isset($_GET['page']) ? intval($_GET['page']) : (isset($_POST['page']) ? intval($_POST['page']) : 1);
$offset = ($page-1) * $perpage;
$cityArr = array();
$citys = $db->sql_query("SELECT id, title FROM {$prefix}_city");
while (list($city_id, $city_title) = $db->sql_fetchrow()) $cityArr[$city_id] = $city_title;
$total = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_district WHERE alanguage='$currentlang'  "));
$result = $db->sql_query("SELECT  id, title, weight,cityid  FROM ".$prefix."_district  WHERE alanguage='$currentlang' AND cityid='$cityid' ORDER BY weight ASC LIMIT $offset, $perpage");
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
echo "<td class=\"row1sd\" width=\"88%\">"._DISTRICTNAME."</td>\n";
echo "<td class=\"row1sd\" width=\"85%\">"._CITY."</td>\n";
echo "<td class=\"row1sd\" align=\"center\" width=\"30\">"._EDIT."</td>\n";
echo "<td class=\"row1sd\" align=\"center\" width=\"30\">"._DELETE."</td>\n";
echo "</tr>\n";
$cur_ar = array(_VND,_USD);
$i =0;
while(list($id, $title, $weight,$cityid) = $db->sql_fetchrow($result)) {
if($i%2 == 1) { $css = "row1"; }	else { $css ="row3"; }
echo "<tr>\n";
echo "<td class=\"$css\" align=\"center\"><input type=\"text\" name=\"poz[$id]\" value=\"$weight\" size=\"2\"  style=\"text-align:center;color:red;\"></td>\n";
//echo "<td class=\"$css\"><a href=\"?f=district&do=edit&id=$id\" info=\""._EDIT."\"><b>$title</b></a></td>\n";
if($ajax_active == 1) {
	echo "<td class=\"row1\" id=\"".$adm_modname."_title_edit_".$mid."\"><a href=\"modules.php?f=$adm_modname&do=edit&id=$id\" title=\""._QUICK_EDIT."\" onclick=\"return show_edit_title($id,'$title','$adm_modname',30,'"._SAVECHANGES."','');\"><b>$title</b></a></td>\n";
} else {
	echo "<td class=\"row1\"><b>$title</b></td>\n";
}
echo "<td class=\"$css\" align=\"center\">{$cityArr[$cityid]}</td>\n";
echo "<td class=\"$css\" align=\"center\" width=\"30\"><a href=\"?f=".$adm_modname."&do=edit&id=$id\" info=\""._EDIT."\"><img border=\"0\" src=\"../images/edit.gif\"></a></td>\n";
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
echo "<tr><td colspan=\"5\" align=\"center\" class=\"row1\"><input type=\"submit\" name=\"fixorder\" value=\""._SAVEORDER."\"></td></tr>\n";
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