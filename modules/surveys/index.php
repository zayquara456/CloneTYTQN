<?php
if (!defined('CMS_SYSTEM')) {
    die();
}

include("header.php");
echo "<div class=\"\" style=\"margin-bottom: 5px; background-color: #F0F0F0; padding: 5px\"><b>"._MODTITLE."</b></div>";
$result = $db->sql_query("SELECT id, question, totalvotes, time FROM ".$prefix."_survey WHERE question!='' AND alanguage='$currentlang' ORDER BY time DESC");
if($db->sql_numrows($result) > 0) {
	echo "<table border=\"0\" width=\"100%\" cellpadding=\"3\" style=\"border-collapse: collapse\">";
	while(list($id, $title, $totalvotes, $time) = $db->sql_fetchrow($result)) {
		echo "	<tr>";
		echo "		<td width=\"12\">";
		echo "		<img border=\"0\" src=\"images/survey.gif\" width=\"12\" height=\"10\"></td>";
		echo "		<td><a href=\"".url_sid("index.php?f=".$module_name."&do=detail&id=$id")."\"><b>$title</b></a> <span class=\"grey\">(".ext_time($time,2).")</span></td>";
		echo "		<td width=\"80\"><b>$totalvotes</b> "._ANWSER."</td>";
		echo "		<td align=\"right\" width=\"80\"><a href=\"".url_sid("index.php?f=".$module_name."&do=detail&id=$id")."\">"._DETAIL."</a></td>";
		echo "	</tr>";
	}	
	echo "</table>";	
}else{
OpenTable();
echo "<center>"._NODATA."</center>";
CloseTable();
}	

include("footer.php");

?>