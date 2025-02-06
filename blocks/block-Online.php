<?php

if (!defined('CMS_SYSTEM')) die();

global $onls_g, $statcls, $stathits1;
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
if ($onls_g!="") 
{ 
	$onls_g1 = explode("|",$onls_g); 
	$num_h2 = sizeof($onls_g1); 
} 
else 
{ 
	$num_h2 = 0; 
}
$num_h2 = str_pad( $num_h2, 4, "0", STR_PAD_LEFT );

$content = "<div class=\"div-block\">";
$content .= "<div class=\"div-tblock\">{$correctArr[1]}</div>";
$content .= "<div class=\"div-cblock\"><div   style=\"padding:5px\">"._OLGUESTS.":";
$content .= "&nbsp;&nbsp;&nbsp;<font color=\"red\">".$num_h2."</font></div>";
$content .= "<div  style=\"padding:5px\">"._HITSOL."";
$content .= "&nbsp;&nbsp;&nbsp;<font color=\"red\">".$stathits1."</font></div>";
$content .= "</div>";
$content .= "</div>";

?>
