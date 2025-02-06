<?php
if (!defined('CMS_SYSTEM')) exit;

global $Default_Temp,$path_upload;

$content = '';
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
$result = $db->sql_query("SELECT id,title,images,links FROM ".$prefix."_video WHERE active=1 ORDER BY id desc LIMIT 4");
$i=0;
$content .= "<div class=\"div-block\">";
$content .= "<div class=\"div-tblock\">{$correctArr[1]}</div>";
$content .= "<div class=\"div-cblock\" style=\"text-align:left; padding:5px 0 2px 0;\">";
$content .= "<script type=\"text/javascript\" src=\"js/mediaplayer/swfobject.js\"></script>";
	while(list($id, $title,$images, $links_video) = $db->sql_fetchrow($result))
	{
		if($i==0)
		{
//$content .= "	<script type=\"text/javascript\">";
//$content .= "		swfobject.registerObject(\"player12\",\"9.0.98\",\"js/mediaplayer/expressInstall.swf\");";
//$content .= "	</script>";
$content .= "<div id=\"divplayer12\">";
$content .="<div style=\"font-weight:bold; padding:4px 0px 4px 0px; border-bottom:1px solid #CCC\">$title</div>";
$content .='<iframe width="300" height="200" src="//www.youtube.com/embed/'.$links_video.'?rel=0" frameborder="0" allowfullscreen></iframe>';
//$content .= "	<object id=\"player12\" classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" name=\"player\" width=\"288\" height=\"250\">";
//$content .= "		<param name=\"movie\" value=\"js/mediaplayer/mediaplayer.swf\" />";
//$content .= "		<param name=\"allowfullscreen\" value=\"true\" />";
//$content .= "		<param name=\"allowscriptaccess\" value=\"always\" />";
//$content .= "		<param name=\"flashvars\" value=\"file=$links_video&image=$path_upload/video/$images\" />";
//$content .= "		<object type=\"application/x-shockwave-flash\" data=\"js/mediaplayer/mediaplayer.swf\" width=\"288\" height=\"250\">";
//$content .= "			<param name=\"movie\" value=\"js/mediaplayer/mediaplayer.swf\" />";
//$content .= "			<param name=\"allowfullscreen\" value=\"true\" />";
//$content .= "			<param name=\"allowscriptaccess\" value=\"always\" />";
//$content .= "			<param name=\"flashvars\" value=\"file=$links_video&image=$path_upload/video/$images\" />";
//$content .= "			<p><a href=\"http://get.adobe.com/flashplayer\">Get Flash</a> to see this player.</p>";
//$content .= "		</object>";
//$content .= "	</object>";

$content .="</div>";
	$content .= "<div class=\"video-detail\" style=\"padding:4px 0px 4px 0px;\">";	
		}
		else
		{
			$content.="<div><a href=\"#video\" id=\"video\" title=\"$title\" alt=\"$title\" onclick=\"return show_video_block('$links_video','$path_upload/video/$images','$title');\">".CutString($title,50)."</a></div>";	
		}
	
		$i++;
		
	}
	$content .= "</div>";	
$content .= "</div></div>";
?>
