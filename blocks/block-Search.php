<?php

if (!defined('CMS_SYSTEM')) die();

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
	$content = "<div class=\"div-blsearch\">";
	//$content .= "<div class=\"div-blsearch-title\">{$correctArr[1]}</div>";
$content .= "<div class=\"div-blsearch-conntent\" style=\"padding:10px 5px 10px 5px\">";
$content .= "<form action=\"".url_sid("search.php?f=news")."\" method=\"POST\">";
$content .= "<input style=\"border:1px solid #C1C1C1; background:#fcfcfc; border-radius: 5px 5px 5px 5px; padding:4px; width:180px\" type=\"text\" name=\"q\" class=\"text\">&nbsp;&nbsp;<input type=\"image\" src=\"templates/$Default_Temp/images/btn_search.gif\"  align=\"absmiddle\" class=\"sb_but1\" value=\""._SEARCH."\">";
$content .= "</form>";
$content .= "</div></div>";
?>