<?php
if ((!defined('CMS_SYSTEM')) AND (!defined('CMS_ADMIN'))) {die();}

global $yim_support, $Default_Temp;

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
if(!empty($yim_support)) 
{
	$yim_support_arr = @explode(",", $yim_support);
	//$yim_support_arr = str_replace(" ","",$yim_support_arr);
	$content .= "<div class=\"div-block\">";
	$content .= "<div class=\"div-tblock\">{$correctArr[1]}</div>";
	for ($i = 0; $i < sizeof($yim_support_arr); $i++) 
	{
		$yim_support_arr2 = @explode("|", $yim_support_arr[$i]);
			$content .= "<div class=\"div-cblock\"><div style=\"padding:3px; border-bottom:1px solid #f8f8f8;\"><a href=\"ymsgr:SendIM?".$yim_support_arr2[0]."\"><img border=0 src=\"http://opi.yahoo.com/online?u=".$yim_support_arr2[0]."&m=g&t=0\" align=\"absmiddle\"> ".$yim_support_arr2[1]."</a></div></div>";
	}
	if (count($yim_support_arr) > 0) 
		$content .= "<div class=\"div-fblock\"></div>";
	$content .= "</div>";
}
?>
