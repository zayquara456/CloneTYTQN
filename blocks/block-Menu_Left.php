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
$content .= "<div class=\"div-block\">";
$content .= "<div class=\"div-tblock\">{$correctArr[1]}</div>";
$content .= "<div class=\"div-cblock\">";
$content .= "<div id=\"ddsidemenubar\" class=\"markermenu\">";
$content .= "<ul>";
$i=1;
$result = $db->sql_query("SELECT mid, title, url FROM {$prefix}_leftmenus WHERE parentid=0 and active=1 and alanguage='$currentlang' order by weight asc");
if($db->sql_numrows($result) > 0) 
{
	while (list($mid, $title, $url) = $db->sql_fetchrow($result)) 
	{
		$result_check = $db->sql_query("SELECT mid, title, url FROM {$prefix}_leftmenus WHERE parentid=$mid and active=1 and alanguage='$currentlang' order by weight asc");
		if ($db->sql_numrows($result_check) > 0)
			$content .= "<li><a href=\"".url_sid($url)."\"  rel=\"ddsubmenuside$i\">$title</a></li>";
		else
			$content .= "<li><a href=\"".url_sid($url)."\">$title</a></li>";
		$result_sub = $db->sql_query("SELECT mid, title, url FROM {$prefix}_leftmenus WHERE parentid=$mid and active=1 and alanguage='$currentlang' order by weight asc");
		if($db->sql_numrows($result_sub) > 0) 
		{
			$content .= "<ul id=\"ddsubmenuside$i\" class=\"ddsubmenustyle blackwhite\">";
			while (list($mid_sub, $title_sub, $url_sub) = $db->sql_fetchrow($result_sub)) 
			{
				$content .= "<li><a href=\"".url_sid($url_sub)."\">$title_sub</a>";
				$result_sub2 = $db->sql_query("SELECT mid, title, url FROM {$prefix}_leftmenus WHERE parentid=$mid_sub and active=1 and alanguage='$currentlang' order by weight asc");
				if($db->sql_numrows($result_sub2) > 0) 
				{
					$content .= "<ul>";
					while (list($mid_sub2, $title_sub2, $url_sub2) = $db->sql_fetchrow($result_sub2)) 
					{
						$content .= "<li><a href=\"".url_sid($url_sub2)."\">$title_sub2</a></li>";
					}
					$content .= "</ul>";
				}
				$content .= "</li>";
			}
			$content .= "</ul>";
		}
		$i++;
	}
	$content .= "</ul></div><script type=\"text/javascript\">ddlevelsmenu.setup(\"ddsidemenubar\", \"sidebar\") //ddlevelsmenu.setup(\"mainmenuid\", \"topbar|sidebar\")</script>";
}
$content .= "</div></div>";
?>