<?php
if (!defined('CMS_SYSTEM')) exit;
global $Default_Temp, $urlsite;
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
$content .= "<div style=\"padding-bottom:10px\"><a href=\"http://dichvusuachua.vn/home/dich-vu-bao-dam.asp\"><img src=\"$urlsite/templates/{$Default_Temp}/images/bao-dam.png\" alt=\"bao dam\" /></a></div>";
?>