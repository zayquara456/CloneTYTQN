<?php
if (!defined('CMS_SYSTEM')) exit;


function show_left_menu($idbuc)
{
	if($idbuc!=0)
	{
	global $Default_Temp, $db,$prefix,$currentlang ;
	$content = "";
	$result = $db->sql_query("SELECT mid, title, url FROM {$prefix}_mainmenus WHERE menu_type='left_menu' AND mid=$idbuc and active=1 and alanguage='$currentlang' order by weight asc");
	if($db->sql_numrows($result) > 0) 
	{
		$content .= "<div class=\"fl\" style=\" overflow:hidden\">\n";
		$j=1;
		while (list($mid, $mtitle, $murl) = $db->sql_fetchrow($result)) 
		{
			$content .= "<div class=\"div-block\">\n";
			$content .= "<div class=\"div-tblock\"><a href=\"".url_sid($murl)."\">$mtitle</a></div>\n";
			$content .= "<div class=\"div-cblock\">\n";
			$content .= "<div id=\"my_menu\" class=\"sdmenu\">\n";
			$result_sub = $db->sql_query("SELECT mid, title, url FROM {$prefix}_mainmenus WHERE menu_type='left_menu' AND parentid=$mid and active=1 and alanguage='$currentlang' order by weight asc");
			if($db->sql_numrows($result_sub) > 0) 
			{
				$i=1;
				while (list($mid_sub, $title_sub, $url_sub) = $db->sql_fetchrow($result_sub)) 
				{
					$result_check = $db->sql_query("SELECT mid, title, url FROM {$prefix}_mainmenus WHERE menu_type='left_menu' AND parentid=$mid_sub and active=1 and alanguage='$currentlang' order by weight asc");
				if ($db->sql_numrows($result_check) > 0)
					$content .= " <div><span>$title_sub</span>\n";					
				else
					$content .= " <div><span><a href=\"".url_sid($url_sub)."\">$title_sub</a></span>\n";
					$result_sub2 = $db->sql_query("SELECT mid, title, url FROM {$prefix}_mainmenus WHERE menu_type='left_menu' AND parentid=$mid_sub and active=1 and alanguage='$currentlang' order by weight asc");
					if($db->sql_numrows($result_sub2) > 0) 
					{
						while (list($mid_sub2, $title_sub2, $url_sub2) = $db->sql_fetchrow($result_sub2)) 
						{
							$content .= "<a href=\"".url_sid($url_sub2)."\">$title_sub2</a>\n";
						}
					}
					$i++;
					$content .="</div>";
				}
			}
			$content .= "</div></div></div>\n";
			$j++;
		}
		$content .= "</div>\n";
	}
	return $content;
	}
}
//$bucmenu = isset($_GET['c']) ? $_GET['c'] : "";
//$taobucmenu = isset($_GET['t']) ? $_GET['t'] : "";
//$faobucmenu = isset($_GET['f']) ? $_GET['f'] : "";
//$idbuc=0;

?>