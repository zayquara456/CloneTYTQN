<?php

if(!defined('CMS_SYSTEM')) {
	die();
}

global $db, $prefix, $currentlang;
echo "		<table border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: collapse\" height=\"20\">";
echo "			<tr>";
echo "<td align=\"center\" style=\"border-right: 1px solid #FFF; padding-left: 5px; padding-right: 5px; background: #003262 url(templates/nblaws/images/topmenu_blue.gif) repeat-y top left\">";
if ($currentlang =="english") {
echo "<a class=\"menutop\" href=\"index.php?lang=vietnamese\">Ti&#7871;ng Vi&#7879;t</a>";
} else {
echo "<a class=\"menutop\" href=\"index.php?lang=english\">English</a>";
}
echo "</td>";
$result_mntop = $db->sql_query("SELECT title, url, target FROM ".$prefix."_mainmenus WHERE active='1' AND alanguage='$currentlang' ORDER BY weight");
$i  =0;
if($db->sql_numrows($result_mntop) > 0) {
	while(list($titlemntop, $urlmntop, $targetmntop) = $db->sql_fetchrow($result_mntop)) {
		$i ++;
		if($targetmntop == 1) {
			$targetmntop_ds = "_blank";
		} else {
			$targetmntop_ds = "_self";
		}
		if ($i < $db->sql_numrows($result_mntop)) {
			$mntopcss = " style=\"border-right: 1px solid #FFF; padding-left: 5px\"";	
		} else {
			$mntopcss =" style=\"padding-left: 5px\"";
		}		
		echo "<td width=\"17%\" class=\"menutop_$i\" align=\"center\"{$mntopcss}><a href=\"".url_sid("$urlmntop")."\" target=\"$targetmntop_ds\" class=\"menutop\">{$titlemntop}</a></td>";
	}	
}	

echo "</tr></table>";

?>