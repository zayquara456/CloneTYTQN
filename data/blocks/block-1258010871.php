<?php

if ((!defined('CMS_SYSTEM')) AND (!defined('CMS_ADMIN'))) {
die();
}
$bl_arr = array();
$bl_arr[] = $bl_l;
$bl_arr[] = $bl_r;
$basename = pathinfo(__FILE__, PATHINFO_BASENAME);
$correctArr = array();
for ($i = 0; $i < count($bl_arr); $i++) 
{
	for ($h = 0; $h < count($bl_arr[$i]); $h++) 
	{
		$temp = explode("@", $bl_arr[$i][$h]);
		if (($temp[5] == $currentlang) && ($temp[6] == $basename)) 
		{
			$correctArr = $temp;
			break;
		}
	}
}

$content = "";
$content .= "<div class=\"div-block\">";
$content .= "<div class=\"div-tblock\">{$correctArr[1]}</div>";
$content .= "<div class=\"div-cblock\">";
$content .= "&lt;p&gt;Hotline l&amp;agrave; 1343243942&lt;/p&gt;";
$content .= "</div>";
$content .= "</div>";

?>