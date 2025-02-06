<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

include("page_header.php");
$sql="";

	$sql="SELECT * FROM ".$prefix."_usergroup ORDER BY id DESC";
	$result = $db->sql_query($sql);
ajaxload_content();
echo "<div id=\"pagecontent\">";
echo "<div id=\"".$adm_modname."_main\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
echo "<tr><td colspan=\"6\" class=\"header\">Quản lý nhóm thành viên</td></tr>";
echo "<tr>\n";
echo "<td class=\"row1sd\">Tên nhóm</td>\n";
//echo "<td class=\"row1sd\">Phần trăm nạp thẻ cào (%)</td>\n";
//echo "<td class=\"row1sd\">Phần trăm mua thẻ carot (%)</td>\n";
echo "<td align=\"center\" width=\"30\" class=\"row1sd\">"._EDIT."</td>\n";
echo "<td align=\"center\" width=\"30\" class=\"row3sd\">"._DELETE."</td>\n";
echo "</tr>\n";
$i =0;
while(list($id,$title, $sale, $sale_card, $permission, $active) = $db->sql_fetchrow($result)) {
	$icondel = "<a href=\"?f=$adm_modname&do=deletegroup&id=$id\" onclick=\"return confirm('"._DELETEASK."');\" info=\""._DELETE."\"><img border=\"0\" src=\"images/delete.png\"></a>";
	echo "<tr>\n";
	echo "<td class=\"row1\"><strong>$title</strong></td>\n";
	//echo "<td class=\"row1\"><strong>$sale</strong></td>\n";
	//echo "<td class=\"row1\"><strong>$sale_card</strong></td>\n";
	echo "<td align=\"center\" class=\"row3\"><a href=\"?f=$adm_modname&do=editgroup&id=$id\" info=\""._EDIT."\"><img border=\"0\" src=\"images/edit.png\"></a></td>\n";
	echo "<td align=\"center\" class=\"row1\">$icondel</td>\n";
	echo "</tr>\n";
	$i ++;
}
echo "</table></div>\n";
echo "</div>";
include_once("page_footer.php");
?>