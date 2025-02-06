<?php
if (!defined('CMS_SYSTEM')) exit;
global $Default_Temp,$imgFold,$db, $prefix, $currentlang;
$content = "";
$content .= "<script type=\"text/javascript\">
	// <![CDATA[
	var myMenu;
	window.onload = function() {
		myMenu = new SDMenu(\"my_menu\");
		myMenu.init();
	};
	// ]]>
</script>";

$content .= "<div style=\"float: left\" id=\"my_menu\" class=\"sdmenu\">";	
/*
loc theo quoc gia
*/
$result = $db->sql_query("SELECT catid, title FROM {$prefix}_question_cat WHERE parent=0 and active=1 and alanguage='$currentlang' order by weight asc");
if($db->sql_numrows($result) > 0) 
{
	while (list($bcatid, $btitle) = $db->sql_fetchrow($result)) 
	{
	$content .= "<div>";
	$content .= "<span><a href=\"".fetch_urltitle("index.php?f=question&do=categories&id=$bcatid",$btitle)."\">$btitle</a></span>";
	$result_sub = $db->sql_query("SELECT catid, title FROM {$prefix}_question_cat WHERE parent=$bcatid and active=1 and alanguage='$currentlang' order by weight asc");
	if($db->sql_numrows($result_sub) > 0) 
	{
		while (list($catid_sub, $title_sub) = $db->sql_fetchrow($result_sub)) 
		{
			$content .= "<a href=\"".fetch_urltitle("index.php?f=question&do=categories&id=$catid_sub",$title_sub)."\">$title_sub</a>";
		}
	}
	$content .= "</div>";
	}
}

$content .= "</div>";
//$content .= "</div>";

?>