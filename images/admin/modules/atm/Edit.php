<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$err = $err_title= $err_district = $err_address = "";
$id = intval($_GET['id']);
$cityid = intval($_GET['cid']);
$result = $db->sql_query("SELECT title, atm, address, districtid FROM ".$prefix."_atm WHERE id='$id'");
if(empty($id) || $db->sql_numrows($result) != 1) {
	header("Location: modules.php?f=".$adm_modname."&id=$cityid");
	exit;
}	

list($title, $atm, $address, $districtid) = $db->sql_fetchrow($result);

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
		
	if(!$err) {
		$db->sql_query("UPDATE ".$prefix."_atm SET title='$title', districtid='$districtid', address='$address', atm='$atm'  WHERE id='$id'");
		updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _EDIT);
		header("Location: modules.php?f=".$adm_modname."&id=$cityid");
	}	
}	

include("page_header.php");

echo "<br><form method=\"POST\" onsubmit=\"if(this.title.value=='') { this.title.focus(); return false; }\"><table width=\"80%\" border=\"0\" align=\"center\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
echo "<tr><td class=\"header\" colspan=\"2\">"._MODTITLE." &raquo; "._EDIT_ATM."</td></tr>";

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
while(list($dis_id, $dis_title) = $db->sql_fetchrow($result_district)) {
	if ($districtid == $dis_id){$seld =" selected";}
	else{$seld ="";	}
	echo "<option name=\"districtid\" value=\"$dis_id\" $seld>$dis_title</option>";
}
echo "</select></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._ADDRESS."</b></td>\n";
echo "<td class=\"row3\">$err_address<textarea type=\"text\" name=\"address\" cols=\"50\" rows=\"3\">$address</textarea></td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td class=\"row3\" colspan=\"2\" align=\"center\"><input type=\"hidden\" name=\"subup\" value=\"1\"><input type=\"submit\" name=\"submit\" value=\""._SAVECHANGES."\" class=\"input1\"></td>\n";
echo "</tr>\n";
echo "</table></form><br>";

include("page_footer.php");

?>