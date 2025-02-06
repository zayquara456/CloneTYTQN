<?php
if (!defined('CMS_SYSTEM')) die();

//link title

if($parent != 0) {
	$title_cat = page_tilecat($catid, $parent, $catname);
	$title_home = "<a href=\"".url_sid("index.php")."\" \" title=\""._HOMEPAGE."\">"._HOMEPAGE."</a> &gt; ".$title_cat." &gt; ".$title."";
} else {
	$catname2 = "<a href=\"".url_sid("index.php?f=".$module_name."&do=categories&id=$catid")."\" >$catname</a>";
	$title_home = "<a href=\"".url_sid("index.php")."\" \" title=\""._HOMEPAGE."\">"._HOMEPAGE."</a> &gt; ".$catname2." &gt; ".$title."";
}

include_once("header.php");
OpenTab($title_home);
//////////////////////////////////DANH SACH MENU//////////////////////////////////////////////////
$resultcat = $db->sql_query("SELECT id, tieude, mua, ban, time, weight, status FROM ".$prefix."_giavang WHERE alanguage='$currentlang' ORDER BY weight,id");
if($db->sql_numrows($resultcat) > 0) {
echo "<br/><form name=\"frm\" action=\"modules.php\" method=\"POST\" onsubmit=\"return checkQuick(this);\">";
echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tbl-main\">\n";
echo "<tr><td colspan=\"9\" class=\"header\">"._LISTGIAVANG." ";
if($_GET['up']=="ok"){echo "<em> - Cập nhật thành công!</em>";}
echo "</td></tr>";
echo "<tr>\n";
echo "<td class=\"row1sd\">"._TITLE."</td>\n";
echo "<td class=\"row1sd\">"._MUA."</td>\n";
echo "<td class=\"row1sd\">"._BAN."</td>\n";
echo "<td align=\"center\" width=\"50\" class=\"row1sd\">"._WEIGHT."</td>\n";
echo "<td align=\"center\" width=\"80\" class=\"row1sd\">"._TIME."</td>\n";
/*echo "<td align=\"center\" width=\"50\" class=\"row1sd\"><b>"._SHOW."</b></td>\n";*/
echo "</tr>\n";
$i=0;
while(list($id, $tieude, $mua, $ban, $time, $weight, $status) = $db->sql_fetchrow($resultcat)) {
$i++;

echo "<tr>\n";
echo "<td class=\"row1\"><b>$tieude</b></td>\n";
echo "<td class=\"row1\">$mua</td>\n";
echo "<td class=\"row1\">$ban</td>\n";
echo "</tr>";
}
echo "</table>";
}
CloseTab();
include_once("footer.php");
?>