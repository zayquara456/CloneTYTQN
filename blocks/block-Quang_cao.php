<?php
if (!defined('CMS_SYSTEM')) exit;
global $Default_Temp;
$bl_arr = array();
$bl_arr[] = $bl_l;
$bl_arr[] = $bl_r;
$basename = pathinfo(__FILE__, PATHINFO_BASENAME);
$correctArr = array();
for ($i = 0; $i < count($bl_arr); $i++) {
	for ($h = 0; $h < count($bl_arr[$i]); $h++) {
		$temp = explode("@", $bl_arr[$i][$h]);
		if (($temp[5] == $currentlang) && ($temp[6] == $basename)) {
			$correctArr = $temp;
			break;
		}
	}
}

$content = "";
$content .= "<table style=\"background:#ffffff url(templates/{$Default_Temp}/images/bg_title.gif)  no-repeat\" width=\"100%\">";
$content .= "<tr><td><h4>&nbsp;{$correctArr[1]}</h4></td></tr>";
$content .= "<tr><td><img src=\"templates/{$Default_Temp}/images/spacer.gif\" height=\"3\" /></td></tr>";
$content .= "<tr><td style=\"background:#dee3e7\" align=\"center\">";
$content .= "</td></tr>";
$content .= "</table>";
?>