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
$content = '';
$content .= "<table style=\"background:#e7e7ea; \" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">";
$content .= "<tr><td style=\"background:#d0cfd5; border-top:1px solid #fffff; border-bottom:1px solid #9f9ea5;padding:3px 0px 5px 0px\"><h4><b>{$correctArr[1]}</b></h4></td></tr>";
//$content .= "<tr><td><img src=\"templates/{$Default_Temp}/images/spacer.gif\" height=\"3\" /></td></tr>";
$content .= "<tr><td><div id=\"ddsidemenubar\" class=\"markermenu\">";
$content .= "<ul>";
$i=1;
$result = $db->sql_query("SELECT catid, title FROM {$prefix}_products_cat WHERE parentid=0 and alanguage='$currentlang'");
if($db->sql_numrows($result) > 0) {
	while (list($idpc, $titlepc) = $db->sql_fetchrow($result)) {
		$content .= "<li><a href=\"index.php?f=products&do=categories&catid=$idpc\"  rel=\"ddsubmenuside$i\">$titlepc</a></li>";
		$i++;
	}
	$content .= "</ul></div><script type=\"text/javascript\">ddlevelsmenu.setup(\"ddsidemenubar\", \"sidebar\") //ddlevelsmenu.setup(\"mainmenuid\", \"topbar|sidebar\")</script>";
}

$content .= "</td></tr></table>";
$j=1;
$result_sub = $db->sql_query("SELECT catid, title FROM {$prefix}_products_cat WHERE parentid=0 and alanguage='$currentlang'");
if($db->sql_numrows($result_sub) > 0) {
	while (list($idpc_sub, $titlepc_sub) = $db->sql_fetchrow($result_sub)) {

		$result_sub2 = $db->sql_query("SELECT catid, title FROM {$prefix}_products_cat WHERE parentid=$idpc_sub and alanguage='$currentlang'");
		if($db->sql_numrows($result_sub2) > 0) {

			$content .= "<ul id=\"ddsubmenuside$j\" class=\"ddsubmenustyle blackwhite\">";
			while (list($idpc_sub2, $titlepc_sub2) = $db->sql_fetchrow($result_sub2)) {
				$content .= "<li><a href=\"index.php?f=products&do=categories&catid=$idpc_sub2\">$titlepc_sub2</a></li>";

			}
			$j++;
			$content .= "</ul>";
		}
		else
		{
			$content .= "<ul id=\"ddsubmenuside$j\" class=\"ddsubmenustyle blackwhite\"><li></li></ul>";
			$j++;
		}
	}
}
?>